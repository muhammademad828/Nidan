/**
 * Creates a debounced version of fn that delays invocation by wait ms.
 */
export function debounce(fn, wait = 300) {
    let timer
    return function (...args) {
        clearTimeout(timer)
        timer = setTimeout(() => fn.apply(this, args), wait)
    }
}
