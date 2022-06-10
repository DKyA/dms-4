const active = "c-infobar_pu--shown";
const hiding = "c-infobar_pu--default";


function ipu() {

    let bars = document.querySelectorAll(".c-infobar__js");
    let popups = document.querySelectorAll(".c-infobar_pu");

    init_close_btn(popups, bars);

    for (let i = 0; i < bars.length; i++) {

        bars[i].addEventListener("pointerdown", () => {

            bars[i].ariaExpanded = (bars[i].ariaExpanded == 'true') ? 'false' : 'true';

            if(popups[i].classList.contains(active)) {
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
                    popups[i].classList.remove(hiding)
                }
                popups[i].classList.add(active);
            }

        });
    }
}

function init_close_btn(popups: NodeListOf<Element>, bars: NodeListOf<Element>) {
    let close_btns = document.querySelectorAll(".c-infobar_pu__close");
    for(let i = 0; i < close_btns.length; i++) {
        close_btns[i].addEventListener("click", () => {
            bars[i].ariaExpanded = (bars[i].ariaExpanded == 'true') ? 'false' : 'true';
            close_p(popups, i)
        });
    }

}


function close_p(popups: NodeListOf<Element>, i: number) {
    console.log(i);
    popups[i].classList.remove(active);
    popups[i].classList.add(hiding);

}

ipu();