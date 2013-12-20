/*!
* zzChat - HTML5 Chat Application
*
* Send message box view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define([
        'jquery',
        'underscore',
        'backbone',
        'i18next',
        'views/disposable',
        'text!templates/sendbox.html'
    ],
    function($, _, Backbone, i18n, DisposableView, sendbox){
        'use strict';

        var SendBoxView = DisposableView.extend({
            template: _.template(sendbox),

            events : {
                "click #sendResponse": "triggerMessage",
                "click .format-toolbar button": "formatBtnClick",
                "keyup #responseText": "onKeyUp",
                "keydown #responseText": "onKeyDown",
            },

            currentText: '',

            render: function() {
                this.$el.html(this.template({
                    i18n: i18n,
                }));

                // Placeholder support for IE9 and others fu**ing browers.
                $('input, textarea', this.$el).placeholder();

                this.$('#responseText').val(this.currentText);

                return this;
            },

            onDispose: function() {
                this.currentText = this.$('#responseText').val();
            },

            triggerMessage: function(e) {
                e.preventDefault();
                // Signal message ready to send
                this.trigger('sendbox:message', this.$('#responseText').val(), 'bbcode');
                // Clear input value
                this.$('#responseText').val('');
            },

            formatBtnClick: function(e) {
                e.preventDefault();

                var button_tag = $(e.currentTarget).data("bbcode-tag");
                var start = '['+button_tag+']';
                var end = '[/'+button_tag+']';

                this.insertTag(start, end);
                return false;
            },

            insertTag: function(start, end) {
                var $textarea = this.$( "#responseText" );
                var element = $textarea.get(0);
                $textarea.focus();
                if (document.selection) {
                   sel = document.selection.createRange();
                   sel.text = start + sel.text + end;
                } else if (element.selectionStart || element.selectionStart == '0') {
                   var startPos = element.selectionStart;
                   var endPos = element.selectionEnd;
                   $textarea.val(element.value.substring(0, startPos) + start + element.value.substring(startPos, endPos) + end + element.value.substring(endPos, element.value.length));
                   this.selectRange(startPos + start.length, endPos+start.length);
                } else {
                    element.value += start + end;
                }
            },

            selectRange: function(start, end) {
                if(!end) end = start;
                var textarea = this.$( "#responseText" ).get(0);

                if (textarea.setSelectionRange) {
                    textarea.focus();
                    textarea.setSelectionRange(start, end);
                } else if (textarea.createTextRange) {
                    var range = textarea.createTextRange();
                    range.collapse(true);
                    range.moveEnd('character', end);
                    range.moveStart('character', start);
                    range.select();
                }
            },

            onKeyUp: function(e) {
                if(e.which == 17) this.isCtrl=false;
            },

            onKeyDown: function (e) {
                if(e.which == 17) this.isCtrl=true;
                if (e.which == 66 && this.isCtrl == true) // CTRL + B, bold
                {
                    this.insertTag('[b]', '[/b]');
                    return false;
                }
                else if (e.which == 73 && this.isCtrl == true) // CTRL + I, italic
                {
                    this.insertTag('[i]', '[/i]');
                    return false;
                }
                else if (e.which == 85 && this.isCtrl == true) // CTRL + U, underline
                {
                    this.insertTag('[u]', '[/u]');
                    return false;
                }
            },

        });
        return SendBoxView;
    }
);