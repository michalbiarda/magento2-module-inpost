/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

define(['jquery'], function ($) {
    'use strict';

    return function () {
        if (typeof Packaging !== "undefined") {
            var config;

            var initialize = Packaging.prototype.initialize;
            Packaging.prototype.initialize = function (params) {
                config = params;
                initialize.apply(this, arguments);
                if (config['mbinpost']['enabled']) {
                    $('select[name=container_weight_units] > option[value!=KILOGRAM]').remove();
                    $('select[name=container_dimension_units] > option[value!=CENTIMETER]').remove();
                }
            };

            var _recalcContainerWeightAndCustomsValue = Packaging.prototype._recalcContainerWeightAndCustomsValue;
            Packaging.prototype._recalcContainerWeightAndCustomsValue = function (container) {
                if (config['mbinpost']['enabled']) {
                    var $container = $(container);
                    var $section = $container.closest('section');
                    if ($section.find('select[name=package_container]').val() !== '') {
                        return;
                    }
                }
                _recalcContainerWeightAndCustomsValue.apply(this, arguments);
            };

            var changeContainerType = Packaging.prototype.changeContainerType;
            Packaging.prototype.changeContainerType = function (obj) {
                if (config['mbinpost']['enabled']) {
                    var $obj = $(obj);
                    var $table = $obj.closest('table');
                    var code = $obj.val();
                    if (code !== '') {
                        $table.find('input[name=container_weight]').attr('disabled', true)
                            .val(config['mbinpost']['containerTypes'][code].weight);
                        $table.find('input[name=container_width]').attr('disabled', true)
                            .val(config['mbinpost']['containerTypes'][code].width);
                        $table.find('input[name=container_height]').attr('disabled', true)
                            .val(config['mbinpost']['containerTypes'][code].height);
                        $table.find('input[name=container_length]').attr('disabled', true)
                            .val(config['mbinpost']['containerTypes'][code].length);
                        $table.find('select[name=container_dimension_units], select[name=container_weight_units]')
                            .attr('disabled', true);
                    } else {
                        $table.find('input[name=container_weight], input[name=container_width], input[name=container_height], input[name=container_length]')
                            .removeAttr('disabled').val('');
                        $table.find('select[name=container_dimension_units], select[name=container_weight_units]')
                            .removeAttr('disabled');
                    }
                }
                changeContainerType.apply(this, arguments);
            }
        }
    };

});