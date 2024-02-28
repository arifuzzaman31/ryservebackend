export default {

    methods: {

    //   playSound(sound_url) {
        //     var audio = new Audio(sound_url);
    //     audio.play();
    //   },

    async getUserToken(){
        let token = null
        const tok = await localStorage.getItem('authuser')
        if(tok){
            let tkn = JSON.parse(tok)
            token = tkn.token
        }
        return token;
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
            return string.replace(/<\/?[^>]+>/ig, " ");
        },



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
