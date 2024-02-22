<script>
import Mixin from "../../mixer";

export default {
    mixins: [Mixin],
    data() {
        return {
        };
    },
    methods: {
        attemptToLogout() {
            try {
                localStorage.removeItem("authuser");
                window.location.href = rootUrl+'login'
            } catch (e) {
                this.validationError({ message: "Something went wrong!" });
            }
        }
    },
};
</script>
<template>
    <li class="menu" id="logoutMenu">
        <a href="javascript:void(0)" type="button" @click="attemptToLogout()" aria-expanded="false" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                <span>Logout</span>
            </div>
        </a>
    </li>
</template>
