<template>
   <section class="section">
        <h1 class="title is-1">
            {{ title }}
        </h1>
        <pre>{{ result }}</pre>
        <!-- Fire "submit", prevent default behaviour (page refresh) -->
        <form @submit.prevent="onFormSubmit">
             <textarea 
             v-model="text" 
             placeholder="add multiple lines"
             style="width:300px; height:300px;"
             ></textarea>
            <button class="button is-primary" type="submit">Submit</button>
        </form>
    </section>
</template>

<script>
 export default {
        data() {
            return {
                title: 'Dlp Validation',
                text: '',
                result: '',
            }
        },
        methods: {
            insertAtStringPos(el, pos, insertable) {
                if(!el.children.length) {
                    var text      = el.outerText;
                    var beginning = document.createTextNode(text.substr(0, pos));
                    var end       = document.createTextNode(text.substr(pos - text.length));

                    while (el.hasChildNodes()) {
                        el.removeChild(el.firstChild);
                    }

                    el.appendChild(beginning);
                    el.appendChild(insertable);
                    el.appendChild(end);
                }
            },

            onFormSubmit() {
                let self = this
                axios.post('api/validate', {
                    text: this.text, 
                    client_id: 1,
                    form_id: 11,
                    user_id: 111,
                    ip: "123.123.123.123",
                    question_id: 101
                }).then(function(response) {
                    self.result = ''

                    response.data.data.map(function(val, key) {
                        if (val.status === 'block') {
                            self.result += '[Red]' + self.text.trim().slice(val.start, val.end) + '[Red]'
                        }
                    })
                })
            }
        }
    }
</script>
