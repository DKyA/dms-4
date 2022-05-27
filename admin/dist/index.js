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
    init_close_btn(popups);
    for (let i = 0; i < bars.length; i++) {
        bars[i].addEventListener("click", () => {
            if (popups[i].classList.contains(active)) {
                close(popups, i);
            }
            else {
                for (let j = 0; j < popups.length; j++) {
                    if (j == i) {
                        continue;
                    }
                    if (popups[j].classList.contains(active)) {
                        close(popups, j);
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
function init_close_btn(popups) {
    let close_btns = document.querySelectorAll(".c-infobar_pu__close");
    for (let i = 0; i < close_btns.length; i++) {
        close_btns[i].addEventListener("click", () => {
            close(popups, i);
        });
    }
}
function close(popups, i) {
    console.log(i);
    popups[i].classList.remove(active);
    popups[i].classList.add(hiding);
}
ipu();
