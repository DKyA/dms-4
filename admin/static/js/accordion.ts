const accordions = qsa("[accordion-selector]");
const buttons = qsa("[accordion-toggle]");

buttons.forEach((b, i) => {
    b.addEventListener("pointerdown", (e) => {

        let a = accordions[i]
        a.classList.toggle(a.classList[0] + '--active');
        b.classList.toggle(b.classList[0] + '--active');

        // Transform elements into one HTDOM thing => do styles magic.

    });
});