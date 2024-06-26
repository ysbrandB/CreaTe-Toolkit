import {computed, ref} from "vue";
const screens = {
    sm: 640,
    md: 768,
    lg: 1024,
    xl: 1280
}

export enum Breakpoints {
    sm = 'sm',
    md = 'md',
    lg = 'lg',
    xl = 'xl',
}
const w = ref(window.innerWidth);

const breakpoint = computed(()=>{
    if (w.value <= screens.md ) return Breakpoints.sm
    else if (w.value >= screens.md && w.value <= screens.lg) return Breakpoints.md
    else if (w.value >= screens.lg && w.value <= screens.xl) return Breakpoints.lg
    else return Breakpoints.xl
})
const debounce = function(func: any, wait: number) {
    let timeout: ReturnType<typeof setTimeout> | undefined
    return () => {
        const later = function() {
            timeout = undefined
        }
        const callNow = !timeout
        if(timeout) {
            clearTimeout(timeout)
        }
        timeout = setTimeout(later, wait)
        if (callNow) func()
    }
}

window.addEventListener(
    'resize',
    debounce(() => {
        w.value = window.innerWidth
    }, 200),
    false
)

w.value = window.innerWidth
export default breakpoint
