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