export default {

    methods: {

    //   playSound(sound_url) {
        //     var audio = new Audio(sound_url);
    //     audio.play();
    //   },

        getUserInfo() {
            let user = null
            const tok = localStorage.getItem('authuser')
            if (tok) {
                let tkn = JSON.parse(tok)
                user = tkn.user
            }
            return user;
        },
    async getUserToken(){
        let token = null
        const tok = await localStorage.getItem('authuser')
        if(tok){
            let tkn = JSON.parse(tok)
            token = tkn.token
        }
        return token;
    },
    getUserPermission(){
        let permission = null
        const user = localStorage.getItem('authuser')
        if(user){
            let usr = JSON.parse(user)
            if((usr.user.userType == 'BUSINESS_OWNER') || (usr.user.userType =='CRM_EDITOR')){
                permission = ['branch-view','branch-edit','branch-delete','branch-create',
                'listing-view','listing-edit','listing-delete','listing-create',
                'listing-type-view','listing-type-edit','listing-type-delete','listing-type-create',
                'reservation-view','add-reservation','edit-reservation','delete-reservation',
                'role-employee-view','report-view',,'business-view'
                ]
                if(usr.user.userType =='CRM_EDITOR') permission.push('amenities-view','vendor-view')
            }else{
                permission = usr.user.roles.permissions
            }
            // permission = usr.roles.permissions
        }
        return permission;
    },

    successMessage(data) {

        // if (data.status === 'success') {
            //   this.playSound(base_url + 'audio/success.mp3');
        // }
        // else {
            //   this.playSound(base_url + 'audio/error.mp3');
        // }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: data.status,
            title: data.message
        })
    },

        validationError(data = {}) {
            // this.playSound(base_url + 'audio/error.mp3');
            Swal.fire({
                icon: data.status ? data.status : 'error',
                title: 'Oops...',
                text: data.message ? data.message : 'Please Fill Form Correctly',
            });
        },
        dateToString(datePassed) {

            const newYears = new Date(datePassed);
            const formattedDate = newYears.toDateString().slice(3);
            const valuedate = formattedDate.split(' ');
            // console.log(valuedate);
            return valuedate[2] + ' ' + valuedate[1] + ', ' + valuedate[3];
            // const formattedDate = format(newYears.toDateString(), 'MMM dd, yyyy');
            // return formattedDate;

        },
        formatPrice: function (value) {

            return Number(value).toFixed(2);
        },
        strippedContent: function (string) {
            return string.replace(/<\/?[^>]+>/ig, " ").replace(/_/g, " ");
        },

        camalizeString(str) {
            return str.toLowerCase().split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        },
        // removeUnderscore: function(){

        // },

        monthToString(month) {

            // pass  2020
            const newYears = new Date(month);
            const formattedDate = newYears.toDateString().slice(3);
            const valuedate = formattedDate.split(' ');

            return valuedate[1] + ', ' + valuedate[3];
        },

    },

    filters: {
        // formatPrice: function (value) {

        //     return Number(value).toFixed(2);
        // },
    }
}
