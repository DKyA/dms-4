// If accordion does't have a further accordion child, then I can center the content
// If accordion doesn't have any visible child, then I can hide it,

function last_accordion() {

    const body = qsa("[accordion-body]");
    const accordions = qsa("[accordion-self]");
    const last = qsa("[accordion-last]")
    body.forEach((e, i) => {

        let ch = e.children;
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

function improve_accordion_back_animation() {

    const ab = qsa("[accordion-button]");
    const active = "c-accordion--active";
    ab.forEach((f, i) => {        
        f.addEventListener("pointerdown", () => {
            const body = qsae("[accordion-body]")[i];
            if (f.classList.contains(active)) {
                f.classList.toggle(active);
                body.style.maxHeight = 0 + 'px';
                setTimeout(() => {
                    body.style.maxHeight = null;
                }, 500);
            }
            else {
                f.classList.toggle(active);
                    body.style.maxHeight = body.scrollHeight + 'px';
            }
            ab.forEach((e, j) => {
                if (e === f) return;
                let inner_body = qsae("[accordion-body]")[j];
                if (!inner_body.offsetHeight) return;
                inner_body.style.maxHeight = null;
                setTimeout(() => {
                    inner_body.style.maxHeight = inner_body.scrollHeight + 'px';
                }, 500);
            });
        });
    });
}


last_accordion();
improve_accordion_back_animation();

