<?php

namespace Core\Rule;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Models\Log;
use Core\Common\MyCollection;
use Carbon\Carbon;
use Core\Rule\Util;

trait Traits
{
    use Util;
    /**
     * store each char in string to array as elements
     * filter out those denied
     * 
     * @param Array allowsign 
     * 
     * @return Collection 
     */

    public function reference_call_back(String $string, Array $allowSign)
    {
        $container = collect();
        for ($i = 0; $i < strlen($string); $i++) {
            $object = new \stdClass;
            $object->char = $string[$i];
            $object->offset = $i;
            $container->push($object);
        }

        $filtered = $container->filter(function($value, $key) use($allowSign) {
            return Str::contains($value->char, $allowSign);
        });

        return collect(array_values($filtered->toArray()));
    }

    /**
     * caculate start and end position in reference of original string
     * set new attribute of start and end position in rule object
     * 
     * @return Array [rule] | false
     */
    public function check_call_back()
    {   
        $toBeReturn = collect();
        $toBeSave = collect();
        $formID = $this->formID(); //indentify src, diif token means diff src
        $strings = $this->arrange();

        $strings->each(function($string) use($toBeReturn, $toBeSave, $formID) {
            try {
                $reference = $this->reference($string['content'])->toArray();
                $filter = $this->filter($string['content']);

                if (preg_match_all('/'. $this->rule->context .'/', $filter, $matches, PREG_OFFSET_CAPTURE) > 0) {
                    $matches = Arr::first($matches);

                    foreach($matches as $match) {
                        $content = Arr::first($match);

                        $start = $reference[Arr::last($match)]->offset + $string['start'];
                        $end = $reference[Arr::last($match) + strlen($content) - 1]->offset + 1 + $string['start'];
                        
                        // return to frondend
                        $toBeReturn->push($this->extraAttribute($start, $end, $content, $formID));
                        // filter the log to be saved

                        // find the same content with half hour, if yes treat as the single input
                        // if greater than half hour, treat as another input
                        $found = Log::where('content', $content)
                                    ->where('available', true)
                                    ->where('updated_at', '>', Carbon::now()->subMinutes(30)->toDateTimeString()) // record in 30 mins to current time. 
                                    ->first();

                        if (empty($found)) { // new content
                            $toBeSave->push($this->extraAttribute($start, $end, $content, $formID));

                        } else { // has duplication
                            // if same content and position then ditch it from found array to let next duplicated in.
                            if ($this->compare($start, $end, $content, $formID, $found)) { 
                                $this->setAvailFalse($found);
                            } else { 
                                /**
                                 * has same content, position is diff, could be identical record if eg:
                                 * 123@123.com      ~~~~~~~~              
                                 * 123@123.com      123@123.com
                                 *                  123@123.com
                                 * so look for the history record to approve same record
                                 */ 
                                $ps = json_decode($found->position);
                                if (!$this->compare($ps->start, $ps->end, $content, $formID, $found)) {
                                    $toBeSave->push($this->extraAttribute($start, $end, $content, $formID));
                                }
                            }
                        }    
                    }
                }
            } catch (\Exception $e) {
                return $e;
            }
        });

        $this->saveLog($toBeSave);

        return $toBeReturn;
    }

    /**
     * get all emails, put them in an collection
     * 
     * @param String input text
     * @return MyCollection emails
     */
    private function emailParts(String $string)
    {
        $collection = new MyCollection();
        $foundEmail = collect();
        $pattern ='[a-zA-Z0-9_\-\+\.]+@[a-zA-Z0-9\- ]+\.([a-zA-Z]{1,4})(?:\.[a-zA-Z]{2})?';
        if (preg_match_all('/'.$pattern.'/', $string, $matches, PREG_OFFSET_CAPTURE) > 0) {
            $matches = collect(Arr::first($matches));
            $matches->each(function($match) use($foundEmail) {
                $foundEmail->push([
                    'content' => $match[0],
                    'start' => $match[1],
                    'end' => $match[1] + strlen($match[0])
                ]);
            });
        }

        $foundEmail->sortBy([
            ['start', 'asc'],
        ])->each(function($value) use($collection) {
            $collection->addItem($value);
        });

        return $collection;
    }

    /**
     * get the rest other then emails
     * 
     * @param String input text
     * @param MyCollection emails
     * 
     * @return Collection other then emails
     */
    private function nonEmailParts(String $string, MyCollection $foundEmail)
    {
        $zero = 0; // start key of string
        $infinite = strlen($string); // end key of string
        $new = collect();
        $data = collect();

        $iterator = $foundEmail->getIterator();

        if(!$iterator->valid()) { // if there is no email send string collection
            $new->push([
                'content' => strtolower($string),
                'start' => 0,
                'end' => strlen($string)
            ]);
        }
        
        while($iterator->valid()) {
            $previous = $iterator->previousRtn();
            $current = $iterator->current();
            $next = $iterator->nextRtn();

            if (!$previous && $current && $next) {
                if ($current['start'] === $zero) {
                    $data->push([
                        'a' => $current['end'],
                        'b' => $next['start']
                    ]);
                }

                if ($current['start'] > $zero) {
                    $data->push([
                        'a' => $zero,
                        'b' => $current['start']
                    ]);
                }
            }

            if ($previous && $current && $next) {
                if ($current['start'] > $zero) {
                    $data->push([
                        'a' => $previous['end'],
                        'b' => $current['start']
                    ]);
                }

                if ($current['end'] < $infinite) {
                    $data->push([
                        'a' => $current['end'],
                        'b' => $next['start']
                    ]);
                }
            }

            if ($previous && $current && !$next) {
                if ($current['end'] < $infinite) {
                    $data->push([
                        'a' => $current['end'],
                        'b' => $infinite
                    ]);
                }

                if ($current['end'] === $infinite) {
                    $data->push([
                        'a' => $previous['end'],
                        'b' => $current['start']
                    ]);
                }
            }

            if (!$previous && $current && !$next) {
                if ($current['start'] > $zero) {
                    $data->push([
                        'a' => $zero,
                        'b' => $current['start']
                    ]);
                }

                if ($current['end'] < $infinite) {
                    $data->push([
                        'a' => $current['end'],
                        'b' => $infinite
                    ]);
                }
            }

            $iterator->next();
        }

        $data->unique()->each(function($value) use($string, $new) {
            $target = substr($string, $value['a'], $value['b'] - $value['a']);
            $new->push([
                'content' => strtolower($target),
                'start' => $value['a'],
                'end' => $value['b'],
            ]);
        });

        return $new;
    }
}
