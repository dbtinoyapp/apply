

(function($) {
    // Refactor the script below. Hint: Use event-based callback triggers.
    $('document').ready(function() {
        var autoSaveObj = {},
                autoSave = {
                    autoRelease: false,
                    onSave: function() {
                        var e = $(".form-status-container .form-status.draft-on-save");
                        e.show();
                        e.find('span').text(new Date());
                        $('form').find('.btn-unload-draft').show();
                    },
                    onRestore: function() {
                        var e = $(".form-status-container .form-status.draft-on-restore");
                        var resetButton = $("form").find('.btn-unload-draft');
                        e.show();
                        setTimeout(function() {
                            e.fadeOut('slow');
                        }, 1500);
                        setTimeout(function() {
                            if (!resetButton.is(':visible')) {
                                resetButton.fadeIn('slow');
                            }
                        }, 2000);
                    },
                    onRelease: function() {
                        var e = $(".form-status-container .form-status.draft-on-release");
                        var container = $('.form-status-container');
                        e.show();
                        setTimeout(function() {
                            e.fadeOut('slow');
                        }, 1500);
                        $('form').find('.btn-unload-draft').hide();
                        container.find('.form-status.draft-on-save').hide();
                    }
                };

                var initCollectionSelect2 = function(parentEl) {
                    var lastResults = [];

                    $('select:not(".multiline-select")', parentEl).each(function() {
                        $(this).select2();
                    });
                    $('.multiline-select', parentEl).each(function() {
                        $(this).select2({
                            formatResult: formatResult,
                            formatSelection: formatSelection
                        });
                    });
                },
                initSelect2 = function() {
                    $('select:not(".multiline-select")', $('body')).each(function() {
                        $(this).select2();
                    });
                $(".autosuggest-select", $('body')).each(function() {
                    $(this).select2({
                        ajax: {
                            url: function() {
                                var id = this.attr('id').split('-', 2);
                                return this.data('url') + '/' + id[1];
                            },
                            dataType: "json",
                            type: "GET",
                            quietMillis: 100,
                            data: function(term) {
                                return {
                                    query: term
                                };
                            },
                            results: function(data, page) {
                                lastResults = data.results;
                                return data;
                            }
                        },
                        createSearchChoice: function(term) {
                            var text = term + (lastResults.some(function(r) {
                                return r.text === term
                            }) ? "" : " (new)");
                            return {id: term, text: text};
                        },
                        initSelection: function(element, callback) {
                            var elementText = $(element).attr('value');
                            callback({"id": elementText, "text": elementText});
                        }
                    });
                });
            },
            addButtonClickListener = function(e) {

                var $fieldset = $(e.data.fieldset);
                var template = $('span:last', $fieldset)
                        .data('template');

                // find smallest free index number.
                var elementName = $fieldset.attr('id');
                var index = 0;

                while ($fieldset.find('#' + elementName + '-' + index).length) {
                    index += 1;
                }

                var $content = $(template.replace(/__index__/g, index));

                $fieldset.find('legend').after($content);
                initButtons($content);
                $content.hide().fadeIn();
                initCollectionSelect2($content);
                //$fieldset.parent('form').autoSaveForm();
                return false;

            },
            removeButtonClickListener = function(e)
            {
                var $fieldset = $(e.data.fieldset);
                var $target = $(e.currentTarget).parent();

                $target.fadeOut(function() {
                    $target.detach();
                    $target.remove();
                });
                //.hide().remove();

                return false;
            },
            initButtons = function(parent)
            {
                parent.find('a.add-item').on('click.formcollection', {fieldset: parent}, addButtonClickListener);
                parent.find('a.remove-item').on('click.formcollection', {fieldset: parent}, removeButtonClickListener);

            };

        function formatResult(item) {
            if (!item.id) {
                // return `text` for optgroup
                return item.text;
            }
            // return item template
            return '<div class="multiline-select-format">' + item.text + '</div>';
        }
        function formatSelection(item) {
            // return selection template
            return '<span>' + item.text + '</span>';
        }

        $.fn.formcollection = function( ) {

            return this.each(function() {
                var collection = $(this);
                if (!collection.is('.form-collection'))
                    return;

                initButtons(collection);
                
            });
        };
        $.fn.autoSaveForm = function() {
            return this.each(function(e, el) {

                el = $(el);

                if (el.length) {
                    autoSaveObj[e] = el.sisyphus(autoSave);
                    el.on('button').on('reset', function() {
                        autoSaveObj[e].manuallyReleaseData();
                    });
                }
            });
        };
        
        

        $.fn.rendered = function(speed, oldCallback) {
          return $(this).each(function() {
            $(this).trigger('beforeRendered');
          });
        },
        
        $.fn.toggleVisibilityFor = function (targetEl) {
            return $(this).each(function() {
                if ($(this).is(':checked')) {
                    targetEl.addClass('hidden');
                } else {
                    targetEl.attr('class', 'form-collection');
                }
            });
        }, 
        $(function() {
            $('.form-collection').formcollection();
            initSelect2();
            $('.freshGradToggle')
            .on('beforeRendered change', function() {
                var employmentCollection = $(this).parents('.form-group').siblings('#cv-employments');
                $(this).toggleVisibilityFor(employmentCollection);
            }).rendered();
            //$('form').autoSaveForm();
        });

    });
})(jQuery);