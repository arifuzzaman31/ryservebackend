<script>
import Mixin from "../../mixer";

export default {
    mixins: [Mixin],
    data() {
        return {
            form: {
                email: "",
                password: "",
                confirm_password: "",
                phone: "",
                first_name: "",
                last_name: "",
            },
            validation_error: {},
        };
    },
    methods: {
        attemptSignin() {
            try {
                axios
                    .post(
                        `${apiUrl}auth/signin?for=register-request`,
                        this.form
                    )
                    .then(response => {
                        if (response.status == 200) {
                            const user = JSON.stringify(response.data);
                            sessionStorage.setItem("authuser", user);
                            this.formReset();
                            window.location.href = baseUrl+'dashboard'
                        }
                    })
                    .catch((e) => {
                        if (e.response.status == 422) {
                            this.validation_error = e.response.data
                        }
                    });
            } catch (e) {
                console.log(e);
                this.validationError({ message: "Something went wrong!" });
            }
        },
        formReset() {
            this.validation_error = {};
            this.form = {
                email: "",
                password: "",
            };
        },
    },
};
</script>

<template>
    <div class="form-form">

        <div class="form-form-wrap">

            <div class="form-container">

                <div class="form-content">

                    <h1 class="">Sign In</h1>

                    <p class="">Log in to your account to continue.</p>



                    <span v-if="validation_error" class="text-danger">

                            <strong>{{ validation_error.message }}</strong>

                        </span>

                    <form class="text-left" @submit.prevent="attemptSignin()">

                        <div class="form">

                            <div id="username-field" class="field-wrapper input">

                                <label for="username">USERNAME</label>

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">

                                        <path

                                            d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"

                                        ></path>

                                        <circle cx="12" cy="7" r="4"></circle>

                                    </svg>

                                <input id="username" v-model="form.email" type="text" class="form-control" placeholder="e.g John_Doe" />

                            </div>



                            <div id="password-field" class="field-wrapper input mb-2">

                                <div class="d-flex justify-content-between">

                                    <label for="password">PASSWORD</label>

                                    <!-- <a href="" class="forgot-pass-link">Forgot Password?</a> -->

                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">

                                        <rect

                                            x="3"

                                            y="11"

                                            width="18"

                                            height="11"

                                            rx="2"

                                            ry="2"

                                        ></rect>

                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>

                                    </svg>

                                <input id="password" v-model="form.password" type="password" class="form-control" placeholder="Password" />

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye">

                                        <path

                                            d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"

                                        ></path>

                                        <circle cx="12" cy="12" r="3"></circle>

                                    </svg>

                            </div>

                            <div class="d-sm-flex justify-content-between">

                                <div class="field-wrapper">

                                    <button type="submit" class="btn btn-primary">

                                            Sign In

                                        </button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
</template>

<style scoped>

</style>
