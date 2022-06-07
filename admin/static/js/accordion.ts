// If accordion does't have a further accordion child, then I can center the content
// If accordion doesn't have any visible child, then I can hide it,

class Accordion {

    public is_available = true;
    public cache: Array<any>;

    tracker(delay = 600, ...args: (number | Element | Element[])[]): boolean {
        // Basically throttle algorithm

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
                if (!this.tracker(400, f, i, ab)) return;
                this.throttled_ac_animation_body(f, i, ab);
            });
        });
    }


    throttled_ac_animation_body(f:Element, i:number, ab:Element[]) {
        const body = qsae("[accordion-body]")[i];
        const active = "c-accordion--active";
        if (f.classList.contains(active)) {
            f.classList.toggle(active);
            body.style.maxHeight = body.scrollHeight + 'px';
            setTimeout(() => {
                body.style.maxHeight = 0 + 'px';
            }, 300);
        }
        else {
            f.classList.toggle(active);
            body.style.maxHeight = body.scrollHeight + 'px';
            setTimeout(() => {
                // Fallback
                body.style.maxHeight = '100vh';
            }, 300)
        }
    }


    last_accordion() {

        const body = qsa("[accordion-body]");
        const accordions = qsa("[accordion-self]");
        const last = qsa("[accordion-last]")
        body.forEach((e, i) => {

            const a = accordions[i];

            if (last[i].children.length === 0) {
                a.classList.add("c-accordion--empty");
                return;
            }

            let is_last = () => {
                for (let c of last[i].children) {
                    if (c.classList.contains("l-component")) return false;
                }
                return true;
            }

            if(is_last()) {
                // There is no accordion, therefore I have to center contents;
                a.classList.add("c-accordion--last");
            }

        });

    }

}


const accordion = new Accordion();
accordion.last_accordion();
accordion.improve_accordion_back_animation();

