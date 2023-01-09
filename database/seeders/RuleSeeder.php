<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rules = collect([
            [
                "name" => "phone_number",
                "policy_id" => 1,
                "context" => "(\+?\(61\)|\(\+?61\)|\+?61|\(0[1-9]\)|0[1-9])?( ?-?[0-9]){7,9}",
                "message" => "phone number or mobile number",
            ],
            [
                "name" => "email",
                "policy_id" => 1,
                "context" => "[a-zA-Z0-9_\-\+\.]+@[a-zA-Z0-9\- ]+\.([a-zA-Z]{1,4})(?:\.[a-zA-Z]{2})?",
                "message" => "email address",
            ],
            [
                "name" => "visa_card",
                "policy_id" => 1,
                "context" => "4\d{12}(?:\d{3})?", //visa card
                "message" => "visa card",
            ],
            [
                "name" => "amx_card",
                "policy_id" => 1,
                "context" => "3[47]\d{13,14}",
                "message" => "american express card",
            ],
            [
                "name" => "master_card",
                "policy_id" => 1,
                "context" => "5[1-5]\d{14}", //master card
                "message" => "master card",
            ],
            [
                "name" => "dinner_card",
                "policy_id" => 1,
                "context" => "(?:3(0[0-5]|[68]\\d)\\d{11})|(?:5[1-5]\\d{14})", //diner club card
                "message" => "dinner card",
            ],
            [
                "name" => "discover_card",
                "policy_id" => 1,
                "context" => "(?:6011\d{12})|(?:65\d{14})", //discover card
                "message" => "discover card",
            ],
            [
                "name" => "jcb_card",
                "policy_id" => 1,
                "context" => "(3\\d{4}|2100|1800)\\d{11}", //jcb card
                "message" => "jcb card",
            ],
            [
                "name" => "swicth_card",
                "policy_id" => 1,
                "context" => "(?:49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})\\d{10}(\\d{2,3})?)|(?:564182\\d{10}(\\d{2,3})?)|(6(3(33[0-4][0-9])|759[0-9]{2})\\d{10}(\\d{2,3})?)", //switch
                "message" => "switch card",
            ],
        ]);

        $rules->each(function($rule) {
            \App\Models\Rule::create($rule);
        });
    }
}
