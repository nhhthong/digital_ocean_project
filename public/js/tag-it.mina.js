! function(t) {
    t.widget("ui.tagit", {
        options: {
            itemName: "item",
            fieldName: "tags",
            availableTags: [],
            tagSource: null,
            removeConfirmation: !1,
            caseSensitive: !0,
            allowSpaces: !1,
            singleField: !1,
            singleFieldDelimiter: ",",
            singleFieldNode: null,
            tabIndex: null,
            onTagAdded: null,
            onTagRemoved: null,
            onTagClicked: null
        },
        _create: function() {
            var i = this;
            if (this.element.is("input") ? (this.tagList = t("<ul></ul>").insertAfter(this.element), this.options.singleField = !0, this.options.singleFieldNode = this.element, this.element.css("display", "block")) : this.tagList = this.element.find("ul, ol").addBack().last(), this._tagInput = t('<input autocomplete="nope" type="password">').addClass("ui-widget-content"), this.options.tabIndex && this._tagInput.attr("tabindex", this.options.tabIndex), this.options.tagSource = this.options.tagSource || function(e, a) {
                    var n = e.term.toLowerCase(),
                        s = t.grep(i.options.availableTags, function(t) {
                            return 0 === t.toLowerCase().indexOf(n)
                        });
                    a(i._subtractArray(s, i.assignedTags()))
                }, this.tagList.addClass("tagit").addClass("ui-widget ui-widget-content ui-corner-all").append(t('<li class="tagit-new"></li>').append(this._tagInput)).click(function(e) {
                    var a = t(e.target);
                    a.hasClass("tagit-label") ? i._trigger("onTagClicked", e, a.closest(".tagit-choice")) : i._tagInput.focus()
                }), this.tagList.children("li").each(function() {
                    t(this).hasClass("tagit-new") || (i.createTag(t(this).html(), t(this).attr("class")), t(this).remove())
                }), this.options.singleField)
                if (this.options.singleFieldNode) {
                    var e = t(this.options.singleFieldNode),
                        a = e.val().split(this.options.singleFieldDelimiter);
                    e.val(""), t.each(a, function(t, e) {
                        i.createTag(e)
                    })
                } else this.options.singleFieldNode = this.tagList.after('<input type="text" style="display:block;" value="" name="' + this.options.fieldName + '">');
            this._tagInput.keydown(function(e) {
                if (e.which == t.ui.keyCode.BACKSPACE && "" === i._tagInput.val()) {
                    var a = i._lastTag();
                    !i.options.removeConfirmation || a.hasClass("remove") ? i.removeTag(a) : i.options.removeConfirmation && a.addClass("remove ui-state-highlight")
                } else i.options.removeConfirmation && i._lastTag().removeClass("remove ui-state-highlight");
                (e.which == t.ui.keyCode.COMMA || e.which == t.ui.keyCode.ENTER || e.which == t.ui.keyCode.TAB && "" !== i._tagInput.val() || e.which == t.ui.keyCode.SPACE && i.options.allowSpaces !== !0 && ('"' != t.trim(i._tagInput.val()).replace(/^s*/, "").charAt(0) || '"' == t.trim(i._tagInput.val()).charAt(0) && '"' == t.trim(i._tagInput.val()).charAt(t.trim(i._tagInput.val()).length - 1) && t.trim(i._tagInput.val()).length - 1 !== 0)) && (e.preventDefault(), i.createTag(i._cleanedInput()), i._tagInput.autocomplete("close"))
            }).blur(function(t) {
                i.createTag(i._cleanedInput())
            }), (this.options.availableTags || this.options.tagSource) && this._tagInput.autocomplete({
                source: this.options.tagSource,
                select: function(t, e) {
                    return "" === i._tagInput.val() && i.removeTag(i._lastTag(), !1), i.createTag(e.item.value), !1
                }
            })
        },
        _cleanedInput: function() {
            return t.trim(this._tagInput.val().replace(/^"(.*)"$/, "$1"))
        },
        _lastTag: function() {
            return this.tagList.children(".tagit-choice:last")
        },
        assignedTags: function() {
            var i = this,
                e = [];
            return this.options.singleField ? (e = t(this.options.singleFieldNode).val().split(this.options.singleFieldDelimiter), "" === e[0] && (e = [])) : this.tagList.children(".tagit-choice").each(function() {
                e.push(i.tagLabel(this))
            }), e
        },
        _updateSingleTagsField: function(i) {
            t(this.options.singleFieldNode).val(i.join(this.options.singleFieldDelimiter))
        },
        _subtractArray: function(i, e) {
            for (var a = [], n = 0; n < i.length; n++) - 1 == t.inArray(i[n], e) && a.push(i[n]);
            return a
        },
        tagLabel: function(i) {
            return this.options.singleField ? t(i).children(".tagit-label").text() : t(i).children("input").val()
        },
        _isNew: function(t) {
            var i = this,
                e = !0;
            return this.tagList.children(".tagit-choice").each(function(a) {
                return i._formatStr(t) == i._formatStr(i.tagLabel(this)) ? void(e = !1) : void 0
            }), e
        },
        _formatStr: function(i) {
            return this.options.caseSensitive ? i : t.trim(i.toLowerCase())
        },
        createTag: function(i, e) {
            if (that = this, i = t.trim(i), !this._isNew(i) || "" === i) return !1;
            var a = t(this.options.onTagClicked ? '<a class="tagit-label"></a>' : '<span class="tagit-label"></span>').text(i),
                n = t("<li></li>").addClass("tagit-choice ui-widget-content ui-state-default ui-corner-all").addClass(e).append(a),
                s = t("<span></span>").addClass("ui-icon ui-icon-close"),
                l = t('<a><span class="text-icon">×</span></a>').addClass("close").append(s).click(function(t) {
                    that.removeTag(n)
                });
            if (n.append(l), this.options.singleField) {
                var o = this.assignedTags();
                o.push(i), this._updateSingleTagsField(o)
            } else {
                var r = a.html();
                n.append('<input type="hidden" style="display:none;" value="' + r + '" name="' + this.options.itemName + "[" + this.options.fieldName + '][]">')
            }
            this._trigger("onTagAdded", null, n), this._tagInput.val(""), this._tagInput.parent().before(n)
        },
        removeTag: function(i, e) {
            if ("undefined" == typeof e && (e = !0), i = t(i), this._trigger("onTagRemoved", null, i), this.options.singleField) {
                var a = this.assignedTags(),
                    n = this.tagLabel(i);
                a = t.grep(a, function(t) {
                    return t != n
                }), this._updateSingleTagsField(a)
            }
            e ? i.fadeOut("fast").hide("blind", {
                direction: "horizontal"
            }, "fast", function() {
                i.remove()
            }).dequeue() : i.remove()
        },
        removeAll: function() {
            var t = this;
            this.tagList.children(".tagit-choice").each(function(i, e) {
                t.removeTag(e, !1)
            })
        }
    })
}(jQuery);
//# sourceMappingURL=tag-it.min.js.map