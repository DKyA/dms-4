function qs(selector: string, parent = document) {
    return parent.querySelector(selector);
}

function qsa(selector: string, parent = document) {
    return [...parent.querySelectorAll(selector)];
}

function sleep(duration:number) {
    // syntaxe sleep(200).then(() => {})
    return new Promise(resolve => {
        setTimeout(resolve, duration)
    })
}

function randint(min:number, max:number) {
    return Math.floor(Math.random() * (max - min + 1) + min)
}

function first(arr:Array<any>, n = 1) {
    if (n === 1) return arr[0]
    return arr.filter((_, index) => index < n)
}

function last(arr:Array<any>, n = 1) {
    if (n === 1) return arr[arr.length - 1]
    return arr.filter((_, index) => arr.length - index <= n)
}

function qsae(selector:string, parent = document) {
    return[...parent.querySelectorAll<HTMLElement>(selector)];
}

function throttle(cb: Function, delay = 1000) {
    let should_wait = false;
    let waiting_args: Array<any>;
    const timeout_f = () => {
        if (waiting_args == null) {
            should_wait = false;
        }
        else {
            cb(...waiting_args);
            waiting_args = null;
            setTimeout(timeout_f, delay);
        }
    }

    return (...args: any) => {

        if (should_wait) {
            waiting_args = args;
            return;
        }

        cb(...args)
        should_wait = true;

        setTimeout(timeout_f, delay);
    }
}