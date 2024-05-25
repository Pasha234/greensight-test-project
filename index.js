const { createApp, ref } = Vue

const alertApp = createApp({
    data() {
        return {
            show: false,
            message: '',
        }
    },
    methods: {
        alertError(message) {
            this.message = message
            this.show = true
            setTimeout(() => {
                this.close()
            }, 4000)
        },
        close() {
            this.show = false
        }
    }
}).mount('#alert-container');

createApp({
    data() {
        return {
            registrationSuccessful: false,
            form: {
                name: {
                    error: "",
                    input: "",
                },
                lastName: {
                    error: "",
                    input: "",
                },
                email: {
                    error: "",
                    input: "",
                },
                password: {
                    error: "",
                    input: "",
                },
                passwordConfirmation: {
                    error: "",
                    input: "",
                }
            }
        }
    },
    methods: {
        sendForm(e) {
            if (!this.validate()) {
                return;
            }
            const thisApp = this;
            axios.post('', e.target, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(function(response) {
                if (response.data.status) {
                    thisApp.registrationSuccessful = true;
                } else {
                    thisApp.alertError(response.data.message);
                }
            }).catch(function(error) {
                thisApp.alertError('Произошла ошибка при отправке формы');
                console.log('Error: ' + error);
            })
        },
        alertError(message) {
            alertApp.alertError(message)
        },
        validate() {
            let noErrors = true;
            for (const [fieldName, field] of Object.entries(this.form)) {
                if (field.input.length == 0) {
                    noErrors = false;
                    this.form[fieldName].error = "Заполните поле";
                }
            }
            if (this.form.password.input != this.form.passwordConfirmation.input) {
                noErrors = false;
                this.form.passwordConfirmation.error = "Пароли не совпадают";
            }
            return noErrors;
        }
    }
}).mount('#main-container')