<script>
import Mixin from "../../mixer";

export default {
    mixins: [Mixin],
    data() {
        return {
            form: {
                email: "",
                password: "",
                confirmPassword: "",
                phoneNumber: "",
                firstName: "",
                lastName: "",
            },
            login: true,
            validation_error: {},
        };
    },
    methods: {
        attemptLogin() {
            try {
                axios
                    .post(
                        `${apiUrl}/auth/signin?for=login-request`,
                        this.form
                    )
                    .then((response) => {
                        if (response.status == 200) {
                            const user = JSON.stringify(response.data);
                            localStorage.setItem("authuser", user);
                            window.axios.defaults.headers[
                                "Authorization"
                            ] = `Bearer ${response.data.token}`;
                            this.formReset();
                            window.location.href = baseUrl+'dashboard'
                        }
                    })
                    .catch((e) => {
                        if (e.response.status == 422) {
                            console.log(e.response);
                            this.validation_error = e.response.data.errors;
                        }
                    });
            } catch (e) {
                console.log(e);
                this.validationError({ message: "Something went wrong!" });
            }
        },
        attemptSignin() {
            try {
                axios
                    .post(
                        apiUrl+"/auth/signin?for=register-request",
                        this.form
                    )
                    .then((response) => {
                        if (response.status == 200) {
                            const user = JSON.stringify(response.data);
                            localStorage.setItem("authuser", user);
                            this.formReset();
                            // window.location.href = baseUrl+'role'
                        }
                    })
                    .catch((e) => {
                        if (e.response.status == 422) {
                            console.log(e.response.data);
                            this.validation_error = e.response.data.errors;
                        }
                    });
            } catch (e) {
                console.log(e);
                this.validationError({ message: "Something went wrong!" });
            }
        },
        toggleForm() {
            this.formReset();
            this.login = !this.login;
        },
        formReset() {
            this.validation_error = {};
            this.form = {
                email: "",
                password: "",
                confirmPassword: "",
                phoneNumber: "",
                firstName: "",
                lastName: "",
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
                    <h1 class="">
                        {{ login == true ? "Sign In" : "Register" }}
                    </h1>
                    <p v-if="login == true" class="">
                        Log in to your account to continue.
                    </p>
                    <p v-else class="signup-link register">
                        Already have an account?
                        <a type="button" @click="toggleForm()">Log in</a>
                    </p>

                    <form
                        class="text-left"
                        @submit.prevent="attemptLogin()"
                        v-if="login == true"
                    >
                        <div class="form">
                            <div
                                id="username-field"
                                class="field-wrapper input"
                            >
                                <label for="username">USERNAME</label>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-user"
                                >
                                    <path
                                        d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                    ></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input
                                    id="Email"
                                    v-model="form.email"
                                    type="text"
                                    class="form-control"
                                    placeholder="e.g John_Doe"
                                />
                                <span
                                    v-if="
                                        validation_error.hasOwnProperty('email')
                                    "
                                    class="text-danger ml-4 mb-4"
                                >
                                    {{ validation_error.email }}
                                </span>
                            </div>

                            <div
                                id="password-field"
                                class="field-wrapper input mb-2"
                            >
                                <div class="d-flex justify-content-between">
                                    <label for="password">PASSWORD</label>
                                    <!-- <a href="" class="forgot-pass-link">Forgot Password?</a> -->
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-lock"
                                >
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
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="form-control"
                                    placeholder="Password"
                                />
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    id="toggle-password"
                                    class="feather feather-eye"
                                >
                                    <path
                                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                                    ></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <span
                                    v-if="
                                        validation_error.hasOwnProperty(
                                            'password'
                                        )
                                    "
                                    class="text-danger ml-4 mb-4"
                                >
                                    {{ validation_error.password }}
                                </span>
                            </div>
                            <div class="d-sm-flex justify-content-between">
                                <div class="field-wrapper">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Sign In
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="signup-link">
                            Not registered ?
                            <a type="button" @click="toggleForm()"
                                >Create an account</a
                            >
                        </p>
                    </form>
                    <form
                        class="text-left"
                        @submit.prevent="attemptSignin()"
                        v-else
                    >
                        <div class="form">
                            <div
                                id="firstname-field"
                                class="field-wrapper input"
                            >
                                <label for="firstname">FIRST NAME</label>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-user"
                                >
                                    <path
                                        d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                    ></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input
                                    id="firstname"
                                    type="text"
                                    v-model="form.firstName"
                                    class="form-control"
                                    placeholder="e.g John"
                                />
                                <span
                                    v-if="
                                        validation_error.hasOwnProperty(
                                            'firstName'
                                        )
                                    "
                                    class="text-danger ml-4 mb-4"
                                >
                                    {{ validation_error.firstName }}
                                </span>
                            </div>
                            <div
                                id="lastname-field"
                                class="field-wrapper input"
                            >
                                <label for="firstname">LAST NAME</label>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-user"
                                >
                                    <path
                                        d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                    ></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input
                                    id="lastname"
                                    type="text"
                                    v-model="form.lastName"
                                    class="form-control"
                                    placeholder="e.g Doe"
                                />
                                <span
                                    v-if="
                                        validation_error.hasOwnProperty(
                                            'lastName'
                                        )
                                    "
                                    class="text-danger ml-4 mb-4"
                                >
                                    {{ validation_error.lastName }}
                                </span>
                            </div>
                            <div id="phone-field" class="field-wrapper input">
                                <label for="phone">PHONE</label>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-user"
                                >
                                    <path
                                        d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                    ></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input
                                    id="phone"
                                    type="text"
                                    v-model="form.phoneNumber"
                                    class="form-control"
                                    placeholder="phone"
                                />
                                <span
                                    v-if="
                                        validation_error.hasOwnProperty(
                                            'phoneNumber'
                                        )
                                    "
                                    class="text-danger ml-4 mb-4"
                                >
                                    {{ validation_error.phoneNumber }}
                                </span>
                            </div>
                            <div id="email-field" class="field-wrapper input">
                                <label for="email">EMAIL</label>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-at-sign register"
                                >
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <path
                                        d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"
                                    ></path>
                                </svg>
                                <input
                                    id="email"
                                    type="text"
                                    v-model="form.email"
                                    class="form-control"
                                    placeholder="Email"
                                />
                                <span
                                    v-if="
                                        validation_error.hasOwnProperty('email')
                                    "
                                    class="text-danger ml-4 mb-4"
                                >
                                    {{ validation_error.email }}
                                </span>
                            </div>
                            <div
                                id="password-field"
                                class="field-wrapper input mb-2"
                            >
                                <div class="d-flex justify-content-between">
                                    <label for="password">PASSWORD</label>
                                    <!-- <a href="" class="forgot-pass-link">Forgot Password?</a> -->
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-lock"
                                >
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
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="form-control"
                                    placeholder="Password"
                                />
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    id="toggle-password"
                                    class="feather feather-eye"
                                >
                                    <path
                                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                                    ></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <span
                                    v-if="
                                        validation_error.hasOwnProperty(
                                            'password'
                                        )
                                    "
                                    class="text-danger ml-4 mb-4"
                                >
                                    {{ validation_error.password }}
                                </span>
                            </div>
                            <div
                                id="password-field"
                                class="field-wrapper input mb-2"
                            >
                                <div class="d-flex justify-content-between">
                                    <label for="password"
                                        >CONFIRM PASSWORD</label
                                    >
                                    <!-- <a href="" class="forgot-pass-link">Forgot Password?</a> -->
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-lock"
                                >
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
                                <input
                                    id="password"
                                    v-model="form.confirmPassword"
                                    type="password"
                                    class="form-control"
                                    placeholder="Password"
                                />
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    id="toggle-password"
                                    class="feather feather-eye"
                                >
                                    <path
                                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                                    ></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <span
                                    v-if="
                                        validation_error.hasOwnProperty(
                                            'confirmPassword'
                                        )
                                    "
                                    class="text-danger ml-4 mb-4"
                                >
                                    {{ validation_error.confirmPassword }}
                                </span>
                            </div>
                            <div class="d-sm-flex justify-content-between">
                                <div class="field-wrapper">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Get Started
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- <p class="signup-link">Not registered ?  <a type="button" @click="toggleForm()">Create an account</a></p> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
