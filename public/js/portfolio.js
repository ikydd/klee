/////////////////////////////
// CONTENTS
/////////////////////////////

/*
 * Event Util
 * Pseudo-Hover
 * Validate Util
 * Element Pinner
 *
 */
 
 var EventUtil = {

	addHandler : function(element, type, handler){
		if (element.addEventListener){
			element.addEventListener(type, handler, false);
		} else if (element.attachEvent){
			//handler = this.thisReplace(handler);
			element.attachEvent("on" + type, handler);
		} else {
			element["on" + type] = handler;
		}
	},
	
	removeHandler : function(element, type, handler){
		if (element.removeEventListener){
			element.removeEventListener(type, handler, false);
		} else if (element.detachEvent){
			//handler = this.thisReplace(handler);
			element.detachEvent("on" + type, handler);
		} else {
			element["on" + type] = handler;
		}
	},
	
	getEvent : function(event){
		return event ? event : window.event;
	},
	
	getTarget : function(event){
		return event.target || event.srcElement;
	},
	
	preventDefault : function(event){
		if (event.preventDefault){
			event.preventDefault();
		} else {
			event.returnValue = false;
		}
	},
	
	thisReplace : function(handler){
		// IE doesn't like have "this" put in eventListeners so...
		// replace each instance of this for window.event.srcElement
		var fn = String(handler).replace("this", "window.event.srcElement");
		eval("var handler = " + fn);
		return handler;
	}
}

var PseudoHover = {

	over : function(event){ // check for _hover before appending if missing
		var element = EventUtil.getTarget(EventUtil.getEvent(event));
		if (element.className.search('_hover') == -1) element.className += '_hover';
	},
	
	out : function(event){ // replace hover with nothing (i.e. remove it)
		var element = EventUtil.getTarget(EventUtil.getEvent(event));
		element.className = element.className.replace('_hover', "");
	},
		
	init : function(element, attribute, value){
		// get relevant elements
		var elements = document.getElementsByTagName(element);
		// iterate over them
		for(var i = 0; i < elements.length; i++){
			// if attribute matches value
			if (elements[i].getAttribute(attribute) == value){
				// attach event handlers
				EventUtil.addHandler(elements[i], "mouseover", function(event){PseudoHover.over(event);});
				EventUtil.addHandler(elements[i], "mouseout", function(event){PseudoHover.out(event);});
			}
		}
	},
	
}

EventUtil.addHandler(window, "load", function(){PseudoHover.init("input", "class", "actionbutton");});

var ValidateUtil = {

	// custom validators can be added to the below list with this
	addValidator : function(name, func){
		this.validators[name] = func;
	},
	
	// the various validators for the input types
	validators : {
		
		alpha : function(input){
			var regex = /^[a-z]+$/i;
			if (regex.test(input)) return true;
			else return 'This may be only letters.';
		},
		
		alphanum : function(input){
			var regex = /^[0-9a-z]+$/i;
			if (regex.test(input)) return true;
			else return 'This may only contain numbers and letters.';
		},
		
		integer : function(input){
			var regex = /^[0-9]+$/;
			if (regex.test(input)) return true;
			else return 'This may be only numbers.';
		},
		
		username : function(input){
			var regex = /^[0-9a-z_]+$/i;
			if (regex.test(input)) return true;
			else return 'This may be only letters, numbers and underscore.'; 
		},
		
		email : function(input){
			var regex = /^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i;
			if (regex.test(input)) return true;
			else return 'This must be a valid email address.';
		},
		
		slug : function(input){
			var regex = /^[a-z0-9-]+$/i;
			if (regex.test(input)) return true;
			else return 'This may contain only characters, numbers and hyphens';
		},
		
		password : function(input){
			var regex = /^[^\\\/\"\']+$/;
			var first = regex.test(input);
			if (!first) return 'This may not contain slashes or quotation marks.';
			
			var regex = /[0-9]+/;
			var second = regex.test(input);
			if (!second) return 'This must contain some numbers.';
			
			var regex = /[a-z]+/i;
			if (regex.test(input)) return true;
			else return 'This must contain some letters.';
		}
	},

	// main function triggered on submit
	submit : function(event){
		var event = EventUtil.getEvent(event)
		// prevent submission
		EventUtil.preventDefault(event);
		// clear any previous feedback
		FeedbackUtil.clear();
		// get form
		var form = EventUtil.getTarget(event);
		// initiliase result
		var valid = 0;
		// iterate over elements
		var i = form.elements.length;
		while(i){
			i--;
			var type = this.sortByTag(form.elements[i]);
			if (type === false) continue;
			
			var result = this.checkByType(form.elements[i], type);
			if (result === false) valid++;
		}
		// go ahead if all is well
		if (valid == 0) { form.submit();}
		//show feedback otherwise
		else { FeedbackUtil.show(); }
	},
	
	sortByTag : function(element){
		switch (element.tagName.toLowerCase()) {
				// just get type for input
				case 'input':
					return element.type;
				// text is set to text type
				case 'textarea':
					return 'text';
				// all the rest get a free pass as they have no data
				case 'fieldset':
				case 'button':
				default:
					return false;
		}
	},
	
	checkByType : function(element, type){
		switch (type.toLowerCase()) {
		
			// these might not need a regex check (eg checkbox, but that's ok
			case 'image':
			case 'file':
			case 'checkbox':
			case 'password':
			case 'text':
				return this.required(element) && this.check(element)
			
			// these ones get a free pass (listed for posterity)
			case 'radio':
			case 'hidden':
			case 'submit':
			case 'reset':
			case 'button':
			default:
				return true;
		}
	},
	
	required : function(element){
		// get data from element
		var req = element.getAttribute('req');
		var value = element.value;
		// if required and value not present...
		if (req && !value){
			FeedbackUtil.addFeedback(element, 'This field is required.');
			return false;
		}
		return true;
	},
	
	check : function(element){
		// get data from element
		var checktype = element.getAttribute('valid');
		var value = element.value;
		// if no checktype then free pass
		if (!checktype) return true;
		if (!value) return true;
		
		var result = this.validate(value, checktype);
		// if result is not true it will be a string error message
		if (result !== true && typeof result == 'string') {
			FeedbackUtil.addFeedback(element, result);
			return false;
		}
		return true;
	},
	
	validate : function(input, check){
		if (this.validators[check]){
			return this.validators[check](input);
		}
		else {
			return 'This fields validator doesn\'t exist.';
		}
	},
	
	init : function(){
		var i = document.forms.length;
		while(i){
			i--;
			// set the onsubmit handler for any forms on the page
			EventUtil.addHandler(document.forms[i], 'submit', function(event){ValidateUtil.submit(event);});
		}
	}
}

var FeedbackUtil = {

	feedback : new Array(),
	
	addFeedback : function(element, msg){
		// add object to feedback array
		this.feedback.push({element : element, message : msg});
	},
	
	messages : new Array(),
	
	show : function(){
		var i = this.feedback.length;
		while(i){
			i--;
			this.signal(this.feedback[i]);
		}
		// reset feedback
		this.feedback = new Array();
	},
	
	signal : function(fb){
		// create span with message
		var span = document.createElement('span');
		span.className = "error";
		span.style.display = 'block';
		span.appendChild(document.createTextNode(fb.message));
		
		// get element and insert before it (to go above?)
		fb.element.parentNode.insertBefore(span, fb.element);
		this.messages.push(span);
	},

	clear : function(){
		var i = this.messages.length;
		while(i){ 
			i--;
			// remove nodes from page one by one
			this.messages[i].parentNode.removeChild(this.messages[i]);
		}
		// then reset the message array
		this.messages = new Array();
	}
}

EventUtil.addHandler(window, "load", function(){ValidateUtil.init();});

function DOMElement(nodename)
{
	this.nodename = nodename;
	this.changes = '';
	this.pos = [0,0];

	this.reset = function()	{
		this.restyle('position','static');
	}
	
	this.restyle = function(attribute, value){
		if (this.node) {this.node.style[attribute] = value;}
	}
	
	this.alter = function(){
		for(var att in this.changes){
			this.restyle(att, this.changes[att]);
		}
	}
	
	this.getPosition = function(obj){
		var curleft = curtop = 0;
		
		if (obj.offsetParent) {
			do {
				curleft += obj.offsetLeft;
				curtop += obj.offsetTop;
			} while ( obj = obj.offsetParent );
		
			return [curleft,curtop];
		}
	}
	this.setup = function()
	{
		this.node = document.getElementById(nodename);
		if (this.node) {
			this.pos = this.getPosition(this.node);
		}
	}
}

var ElementPinner = {

	"elems" : [],
	
	scrollEvent : function(){
		if (this.elems.length > 0){
			var scrollV = (document.body.scrollTop) ? document.body.scrollTop : document.documentElement.scrollTop;
			var scrollH = (document.body.scrollLeft) ? document.body.scrollLeft : document.documentElement.scrollLeft;
			for (i = 0; i < this.elems.length; i++){
				if (scrollV > this.elems[i].trigger){
					if (this.elems[i].changes.position == 'fixed') {this.elems[i].changes.left = this.elems[i].pos[0] - scrollH + 'px';}
					this.elems[i].alter();
				}
				else {
					this.elems[i].reset();
				}
			}
		}
	},
	
	setup : function(){
	
		var a = new DOMElement('portfoliomenu');
		a.setup();
		a.changes = {
			'position' : 'fixed',
			'top' : '12px'
		}
		a.trigger = a.pos[1] - 12;
		this.elems.push(a)
		
		var b = new DOMElement('portfoliopictures');
		b.setup();
		b.changes = {
			'position' : 'relative',
			'left' : (b.pos[0] - a.pos[0]) + 'px'
		}
		b.trigger = a.pos[1] - 12;
		this.elems.push(b);
		
		var func = function(){ElementPinner.scrollEvent();}
		EventUtil.addHandler(window, "scroll", func);
	},
}

EventUtil.addHandler(window, "load", function(){ElementPinner.setup()});