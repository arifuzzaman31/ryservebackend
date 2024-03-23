<script>
import Mixin from "../../mixer";

export default {
    props:["userInfo"],
    mixins: [Mixin],
    data() {
        return {
            url : rootUrl
        };
    }
};
</script>
<template>
    <div class="profile-info">
        <figure class="user-cover-image"></figure>
        <div class="user-info">
            <img :src="url+'admin-assets/assets/img/90x90.jpg'" alt="avatar">
            <h6 class="">{{userInfo.name}}</h6>
            <p class="">{{userInfo.email}}</p>
        </div>
    </div>
    <div class="shadow-bottom"></div>
</template>
