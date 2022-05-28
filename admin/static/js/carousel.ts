class Card {
    card: any;
    width: number;
    active: boolean;
	constructor(card: { clientWidth: any; }) {
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

	set move(x: any) {
		this.purify();
		this.card.classList.add(x);
	}

	get is_hidden() {
		if (this.card.classList.contains(state(4))) return true;
		if (this.card.classList.contains(state(3))) return true;
		return false;
	}
}

class Info{
    wrapper: any;
    cards: any;
    card_width: number;
    wrapper_width: any;
    gap: number;
    control_left: any;
    control_right: any;
    arrows: any;

	constructor(wrapper: Element, cards: any, controls: any[]) {
		this.wrapper = wrapper;
		this.cards = cards;
		this.card_width = parseInt((getComputedStyle(document.querySelector(".c-carousel_card")).width).replace("px", ""));
		// Není potřeba vědět jak, ale tahle věc získá šířku karty
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
	block_arrow(x: string | number) {
		if (this.arrows[x].classList.contains("c-card_carousel__arrow--disabled")) return;
		this.arrows[x].classList.add("c-card_carousel__arrow--disabled");
		this.arrows[x].disabled = true;
	}
	unblock_arrow(x: string | number) {
		if (!this.arrows[x].classList.contains("c-card_carousel__arrow--disabled")) return;
		this.arrows[x].classList.remove("c-card_carousel__arrow--disabled");
		this.arrows[x].disabled = false;
	}
	toggle(x: string | number) {
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
    stack: any[];

	constructor(elements) {
		if (elements == undefined) {
			this.stack = [];
			return;
		}
		this.stack = elements;
	}

	pop_end() {
		let popped = this.stack.pop();
		if(!popped) return false;
		popped.purify()
		popped.g_card.classList.add(state(0));
		popped.active = true;
		return popped;
		// Pro error věci.
	}

	get stackLength() {
		return this.stack.length;
	}
}

class Stack_left extends Stack {
	constructor(elements: any) {
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
		this.stack.push(card)
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
	// Je pro to funkce state(x)
};

const fathers = document.querySelectorAll(".c-card_carousel__js");
const children = (() => {
	let res = [];
	for (let i = 0; i < fathers.length; i++) {
		res.push(fathers[i].children);
	}
	// Původně jsem to tady měl jako js simple object. Asi blbej nápad.
	return res;
})();


function card_carousel() {
	for(let i = 0; i < children.length; i++) {

		const cards = init_cards(children[i]);
		// Mám tady array objektů
		const info = init_info(i, cards);

		const stack_left = new Stack_left(false);
		const stack_right = new Stack_right(Object.assign([], cards)); // Initially...
		// Tady vyrábím kopii, aby neměla vazbu i na originální pole

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
	// Zase, stack metoda
	let active_cols = (() => {
		let res = 0;
		for (let card of cards) {
			if (card.is_hidden) continue;
			// Tohle jsou všechny, které souvisí se skrýváním.
			res++;
		}
		return res
	});

	if (required_cols > active_cols()) {
		let i = required_cols - active_cols();
		while (i > 0) {
			i--;
			if (stack_right.pop_end()) continue;
			if (stack_left.pop_end()) continue;
			break;
		}
	}

	if (required_cols < active_cols()) {
		let x = active_cols() - required_cols
		let i = -1;
		while (x > 0) {
			i++;
			if (cards[i].is_hidden)continue;
			stack_right.fast_push(cards[i]);
			x--;
		}
	}

	arrows(cards, info);
}

function init_move(cards, info, stack_left, stack_right) {
	// let cards = cards_src;
	for (let i in info.controls) {
		info.controls[i].addEventListener("click", () => {
			info.toggle(i);

			let k = (i) ? 0 : 1;
			for (var j = (cards.length-1) * k; j >= 0 && j < cards.length; j = j + 1 - 2*k) {
				// Oboustranný loop (podle vstupu jde od začátku / od konce)
				if (cards[j].is_hidden) continue;
				// Jakmile není hidden, var nechá hodnotu (j), jdu s tím pracovat
				break;
			}
			// Samotné zobrazovací funkce. I tohle by se dalo udělat do jednoho blobu
			// Ale tohle je čitelnější.
			if (i && stack_left.stackLength) {
				stack_right.push_end(cards[j]);
				setTimeout(() => {
					for (let card of cards) {
						if (!card.active) continue;
						card.move = state(2);
					}
				}, 300);
				setTimeout(() => {
					stack_left.pop_end();
					info.toggle(i);
				}, 600);
			}
			if (!i && stack_right.stackLength) {
				stack_left.push_end(cards[j]);
				setTimeout(() => {
					for (let card of cards) {
						if (!card.active) continue;
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
			}, 610)
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
	if (!cards[cards.length-1].is_hidden) {
		info.block_arrow(0);
	}
	if (cards[cards.length-1].is_hidden) {
		info.unblock_arrow(0);
	}
}


function init_cards (raw_cards) {

	let res = [];
	for (let card of raw_cards) {
		res.push(
			new Card(card)
		);
	}
	res = res.reverse();
	return res;

}

function init_info(i, cards) {
	let controls = (() => {
		let base = document.querySelectorAll(".c-card_carousel__arrow");
		let res = [base[0 + 2*i], base[1 + 2*i]];
		return res;
	})();
	// Select controls:
	let info = new Info(fathers[i], cards, controls);
	return info
}

function state(x) {
	return "c-carousel_card" + states[x];
}


card_carousel();