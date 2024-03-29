function qs(selector, parent = document) {
    return parent.querySelector(selector);
}
function qsa(selector, parent = document) {
    return [...parent.querySelectorAll(selector)];
}
function sleep(duration) {
    return new Promise(resolve => {
        setTimeout(resolve, duration);
    });
}
function randint(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}
function first(arr, n = 1) {
    if (n === 1)
        return arr[0];
    return arr.filter((_, index) => index < n);
}
function last(arr, n = 1) {
    if (n === 1)
        return arr[arr.length - 1];
    return arr.filter((_, index) => arr.length - index <= n);
}
function qsae(selector, parent = document) {
    return [...parent.querySelectorAll(selector)];
}
function throttle(cb, delay = 1000) {
    let should_wait = false;
    let waiting_args;
    const timeout_f = () => {
        if (waiting_args == null) {
            should_wait = false;
        }
        else {
            cb(...waiting_args);
            waiting_args = null;
            setTimeout(timeout_f, delay);
        }
    };
    return (...args) => {
        if (should_wait) {
            waiting_args = args;
            return;
        }
        cb(...args);
        should_wait = true;
        setTimeout(timeout_f, delay);
    };
}
class Accordion {
    constructor() {
        this.is_available = true;
    }
    tracker(delay = 600, ...args) {
        if (!this.is_available) {
            this.cache = args;
            return false;
        }
        this.cache = null;
        setTimeout(() => {
            this.is_available = true;
            if (this.cache != null) {
                this.throttled_ac_animation_body(this.cache[0], this.cache[1], this.cache[2]);
            }
        }, delay);
        this.is_available = false;
        return true;
    }
    improve_accordion_back_animation() {
        const ab = qsa("[accordion-button]");
        ab.forEach((f, i) => {
            f.addEventListener("click", () => {
                if (!this.tracker(400, f, i, ab))
                    return;
                this.throttled_ac_animation_body(f, i, ab);
            });
        });
    }
    throttled_ac_animation_body(f, i, ab) {
        const body = qsae("[accordion-body]")[i];
        const active = "c-accordion--active";
        const icon = qsae("[accordion-icon]")[i];
        icon.classList.toggle(icon.classList[0] + '--active');
        if (f.classList.contains(active)) {
            f.classList.toggle(active);
            body.classList.toggle("c-accordion__body--no-animation");
            body.style.maxHeight = body.scrollHeight + 'px';
            setTimeout(() => {
                body.classList.toggle("c-accordion__body--no-animation");
                body.style.maxHeight = 0 + 'px';
            }, 50);
        }
        else {
            f.classList.toggle(active);
            body.style.maxHeight = body.scrollHeight + 'px';
            setTimeout(() => {
                body.style.maxHeight = '100vh';
            }, 210);
        }
    }
    last_accordion() {
        const body = qsa("[accordion-body]");
        const accordions = qsa("[accordion-self]");
        const last = qsa("[accordion-last]");
        body.forEach((e, i) => {
            const a = accordions[i];
            if (last[i].children.length === 0) {
                a.classList.add("c-accordion--empty");
                return;
            }
        });
    }
}
const accordion = new Accordion();
accordion.last_accordion();
accordion.improve_accordion_back_animation();
class Card {
    constructor(card) {
        this.card = card;
        this.width = card.clientWidth;
        this.active = false;
    }
    purify() {
        for (let x of this.card.classList) {
            for (let i in states) {
                if (x == state(i)) {
                    this.card.classList.remove(state(i));
                }
            }
        }
    }
    get g_card() {
        return this.card;
    }
    set move(x) {
        this.purify();
        this.card.classList.add(x);
    }
    get is_hidden() {
        if (this.card.classList.contains(state(4)))
            return true;
        if (this.card.classList.contains(state(3)))
            return true;
        return false;
    }
}
class Info {
    constructor(wrapper, cards, controls) {
        this.wrapper = wrapper;
        this.cards = cards;
        this.card_width = parseInt((getComputedStyle(document.querySelector(".c-carousel_card")).width).replace("px", ""));
        this.wrapper_width = wrapper.clientWidth;
        this.gap = 16;
        this.control_left = controls[1];
        this.control_right = controls[0];
        this.arrows = controls;
    }
    setWidth() {
        this.wrapper_width = this.wrapper.clientWidth;
    }
    setCardWidth() {
        this.card_width = parseInt((getComputedStyle(document.querySelector(".c-carousel_card")).width).replace("px", ""));
        return this.card_width;
    }
    get g_wrapper() {
        return this.wrapper;
    }
    get controls() {
        return {
            0: this.control_left,
            1: this.control_right,
        };
    }
    block_arrow(x) {
        if (this.arrows[x].classList.contains("c-card_carousel__arrow--disabled"))
            return;
        this.arrows[x].classList.add("c-card_carousel__arrow--disabled");
        this.arrows[x].disabled = true;
    }
    unblock_arrow(x) {
        if (!this.arrows[x].classList.contains("c-card_carousel__arrow--disabled"))
            return;
        this.arrows[x].classList.remove("c-card_carousel__arrow--disabled");
        this.arrows[x].disabled = false;
    }
    toggle(x) {
        this.controls[x].classList.toggle("c-card_carousel__arrow--disabled");
        let set_value = (this.controls[x].disabled === true) ? false : true;
        this.controls[x].disabled = set_value;
    }
    get fit() {
        let new_num = Math.floor((this.wrapper_width + this.gap) / (this.setCardWidth() + this.gap) - 0.0001);
        if (new_num <= 1) {
            this.wrapper.style.gridTemplateColumns = "auto";
            return 1;
        }
        if (new_num > this.cards.length) {
            new_num = this.cards.length;
        }
        let string = this.card_width + "px ";
        this.wrapper.style.gridTemplateColumns = string.repeat(new_num);
        return new_num;
    }
}
class Stack {
    constructor(elements) {
        if (elements == false) {
            this.stack = [];
            return;
        }
        this.stack = elements;
    }
    pop_end() {
        let popped = this.stack.pop();
        if (!popped)
            return false;
        popped.purify();
        popped.g_card.classList.add(state(0));
        popped.active = true;
        return popped;
    }
    get stackLength() {
        return this.stack.length;
    }
}
class Stack_left extends Stack {
    constructor(elements) {
        super(elements);
    }
    push_end(card) {
        card.purify();
        card.g_card.classList.add(state(3));
        setTimeout(() => {
            if (card.g_card.classList.contains(state(3))) {
                card.g_card.classList.replace(state(3), state(4));
            }
        }, 300);
        this.stack.push(card);
        card.active = false;
    }
    fast_push(card) {
        card.purify();
        card.g_card.classList.add(state(4));
        this.stack.push(card);
        card.active = false;
    }
}
class Stack_right extends Stack {
    constructor(elements) {
        super(elements);
    }
    push_end(card) {
        card.purify();
        card.g_card.classList.add(state(3));
        setTimeout(() => {
            if (card.g_card.classList.contains(state(3))) {
                card.g_card.classList.replace(state(3), state(4));
            }
        }, 300);
        this.stack.push(card);
        card.active = false;
    }
    fast_push(card) {
        card.purify();
        card.g_card.classList.add(state(4));
        this.stack.push(card);
        card.active = false;
    }
}
const states = {
    0: "--appear",
    1: "--move-left",
    2: "--move-right",
    3: "--stack-hide",
    4: "--hidden"
};
const fathers = document.querySelectorAll(".c-card_carousel__js");
const children = (() => {
    let res = [];
    for (let i = 0; i < fathers.length; i++) {
        res.push(fathers[i].children);
    }
    return res;
})();
function card_carousel() {
    for (let i = 0; i < children.length; i++) {
        const cards = init_cards(children[i]);
        const info = init_info(i, cards);
        const stack_left = new Stack_left(false);
        const stack_right = new Stack_right(Object.assign([], cards));
        init_move(cards, info, stack_left, stack_right);
        const resize_event = new ResizeObserver(() => {
            info.setWidth();
            display_adequate_no_of_cards(info, cards, stack_left, stack_right);
        });
        resize_event.observe(info.g_wrapper);
    }
}
function display_adequate_no_of_cards(info, cards, stack_left, stack_right) {
    let required_cols = info.fit;
    let active_cols = (() => {
        let res = 0;
        for (let card of cards) {
            if (card.is_hidden)
                continue;
            res++;
        }
        return res;
    });
    if (required_cols > active_cols()) {
        let i = required_cols - active_cols();
        while (i > 0) {
            i--;
            if (stack_right.pop_end())
                continue;
            if (stack_left.pop_end())
                continue;
            break;
        }
    }
    if (required_cols < active_cols()) {
        let x = active_cols() - required_cols;
        let i = -1;
        while (x > 0) {
            i++;
            if (cards[i].is_hidden)
                continue;
            stack_right.fast_push(cards[i]);
            x--;
        }
    }
    arrows(cards, info);
}
function init_move(cards, info, stack_left, stack_right) {
    for (let a in info.controls) {
        let i = parseInt(a);
        info.controls[i].addEventListener("click", () => {
            info.toggle(i);
            let k = (i > 0) ? 0 : 1;
            for (var j = (cards.length - 1) * k; j >= 0 && j < cards.length; j = j + 1 - 2 * k) {
                if (cards[j].is_hidden)
                    continue;
                break;
            }
            if (i == 1 && stack_left.stackLength) {
                stack_right.push_end(cards[j]);
                setTimeout(() => {
                    for (let card of cards) {
                        if (!card.active)
                            continue;
                        card.move = state(2);
                    }
                }, 300);
                setTimeout(() => {
                    stack_left.pop_end();
                    info.toggle(i);
                }, 600);
            }
            if (i == 0 && stack_right.stackLength) {
                stack_left.push_end(cards[j]);
                setTimeout(() => {
                    for (let card of cards) {
                        if (!card.active)
                            continue;
                        card.move = state(1);
                    }
                }, 300);
                setTimeout(() => {
                    stack_right.pop_end();
                    info.toggle(i);
                }, 600);
            }
            setTimeout(() => {
                arrows(cards, info);
            }, 610);
        });
    }
}
function arrows(cards, info) {
    if (!cards[0].is_hidden) {
        info.block_arrow(1);
    }
    if (cards[0].is_hidden) {
        info.unblock_arrow(1);
    }
    if (!cards[cards.length - 1].is_hidden) {
        info.block_arrow(0);
    }
    if (cards[cards.length - 1].is_hidden) {
        info.unblock_arrow(0);
    }
}
function init_cards(raw_cards) {
    let res = [];
    for (let card of raw_cards) {
        res.push(new Card(card));
    }
    res = res.reverse();
    return res;
}
function init_info(i, cards) {
    let controls = (() => {
        let base = document.querySelectorAll(".c-card_carousel__arrow");
        let res = [base[0 + 2 * i], base[1 + 2 * i]];
        return res;
    })();
    let info = new Info(fathers[i], cards, controls);
    return info;
}
function state(x) {
    return "c-carousel_card" + states[x];
}
card_carousel();
console.log("TS test");
function move_nav() {
    let base = document.querySelector("#nav_control");
    let nav = document.querySelector(".c-nav");
    if (base) {
        base.addEventListener("click", () => {
            nav.classList.toggle("c-nav--visible");
            base.classList.toggle("c-nav__arrow--extended");
        });
    }
}
move_nav();
const active = "c-infobar_pu--shown";
const hiding = "c-infobar_pu--default";
function ipu() {
    let bars = document.querySelectorAll(".c-infobar__js");
    let popups = document.querySelectorAll(".c-infobar_pu");
    init_close_btn(popups, bars);
    for (let i = 0; i < bars.length; i++) {
        bars[i].addEventListener("pointerdown", () => {
            bars[i].ariaExpanded = (bars[i].ariaExpanded == 'true') ? 'false' : 'true';
            if (popups[i].classList.contains(active)) {
                close_p(popups, i);
            }
            else {
                for (let j = 0; j < popups.length; j++) {
                    if (j == i) {
                        continue;
                    }
                    if (popups[j].classList.contains(active)) {
                        close_p(popups, j);
                    }
                }
                if (popups[i].classList.contains(hiding)) {
                    popups[i].classList.remove(hiding);
                }
                popups[i].classList.add(active);
            }
        });
    }
}
function init_close_btn(popups, bars) {
    let close_btns = document.querySelectorAll(".c-infobar_pu__close");
    for (let i = 0; i < close_btns.length; i++) {
        close_btns[i].addEventListener("click", () => {
            bars[i].ariaExpanded = (bars[i].ariaExpanded == 'true') ? 'false' : 'true';
            close_p(popups, i);
        });
    }
}
function close_p(popups, i) {
    console.log(i);
    popups[i].classList.remove(active);
    popups[i].classList.add(hiding);
}
ipu();
