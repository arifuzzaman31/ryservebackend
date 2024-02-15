
const permission_access = {mounted:(el,bindings) => {
	let permission = localStorage.getItem('permissions')

	if(!permission.includes(bindings.value)){
		el.parentNode.removeChild(el)
	}
}}

export { permission_access };