
export default {
   
        playSound() {
            var audio = new Audio(sound_url);
            audio.play();
        },

        notifying(data){
            const toastMixin = Swal.mixin({
                toast: true,
                icon: 'success',
                title: 'General Title',
                animation: false,
                position: 'top-right',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            
            toastMixin.fire({
                icon: data.status,
                animation: true,
                title: data.message
            });
            
        }
    
}