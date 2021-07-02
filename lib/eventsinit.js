(function (Drupal, $) {
    $(function () {
        /**
         * Events Calendar Functions.
         *
         * Uses Fullcalendar api - see https://fullcalendar.io/docs.
         */
        // Remove term prefix from Event Type option values so filters will work.
        $('#event_type option').each(function (index) {
            var type_option_value = this.value;
            var type_tid_value = type_option_value.replace('term', '');
            this.value = type_tid_value;
        });

        // Trigger filtering.
        $('#event_type').on('change',function () {
            $('#calendar').fullCalendar('rerenderEvents');
        });
        // Date filtering works differently since it's not a taxonomy.
        var $date = $('#event_date');
        var $calendar = $('#calendar');
        // Trigger gotoDate.
        $('#event_date').on('change', function () {
            $calendar.fullCalendar('gotoDate', $(this).val());
        });
        // Page is now ready, initialize the events calendar...
        $('#calendar').fullCalendar({
            // Options and callbacks go here.
            lazyFetching: false,
            height: 'auto',
            minTime: '08:00',
            maxTime: '20:00',
            customButtons: {
                calendar: {
                    text: 'Calendar',
                    click: function () {
                        $('#calendar').fullCalendar('changeView', 'month');
                    }
                }
            },
            header: {
                left: 'agendaDay,basicWeek,month,listMonth,calendar',
                center: '',
                right: 'prev,title,next'
            },
            buttonText: {
                today: 'Today',
                agendaDay: 'Day',
                listDay: 'Day',
                basicWeek: 'Week',
                month: 'Month',
                listMonth: 'List',
                calendar: 'Calendar'
            },
            editable: false,
            eventLimit: true, // for all non-agenda views
            slotEventOverlap: false,
            contentHeight: 'auto',
            views: {
                month: {
                    eventLimit: 99, // Setting ridiculously high because we never want to truncate.
                    eventColor: 'white',   // Background color.
                    eventTextColor: '#454E63', // Charcoal
                    eventBorderColor: 'white',
                    navLinks: true,
                    navLinkDayClick: 'day',
                    columnHeaderFormat: 'dddd',
                },
                basicWeek: {
                    eventLimit: 99, // Setting ridiculously high because we never want to truncate.
                    eventColor: 'white',   // Background color.
                    eventTextColor: '#454E63', // Charcoal
                    eventBorderColor: 'white',
                    navLinks: true,
                    navLinkDayClick: 'day',
                    columnHeaderFormat: 'dddd',
                },
                agendaDay: {
                    eventLimit: 99, // Setting ridiculously high because we never want to truncate.
                    navLinks: true,
                    navLinkDayClick: 'day',
                    columnHeader: false,
                        eventColor: '#f0f6fa',   // Pale grayish-blue.
                    eventTextColor: '#454E63', // Charcoal
                    eventBorderColor: '#a2a6b1',
                },
                listMonth: {
                    listDayFormat: true,
                    listDayAltFormat: false,
                },
            },
            displayEventEnd: true,
            allDaySlot: false,
            slotDuration: '00:10:00',
            slotLabelInterval: '01:00:00',
            // Path to REST view that provides the event data.
            eventSources: [
                {
                    url: '/v1/events_endpoint',
                }
            ],

            eventRender: function eventRender(event, element, view) {
                // Controls display/content of each event.
                var start_date = new Date(event.start);
                var end_date = new Date(event.end);

                // Start date calculations.
                var month_short = start_date.toLocaleString('en-US', { timeZone: 'UTC', month: 'short'});
                var month_long = start_date.toLocaleString('en-US', { timeZone: 'UTC', month: 'long'});
                var day = start_date.toLocaleString('en-US', { timeZone: 'UTC', day: 'numeric'});
                var weekday = start_date.toLocaleString('en-US', { timeZone: 'UTC', weekday: 'long'});
                var year = start_date.toLocaleString('en-US', { timeZone: 'UTC', year: 'numeric'});
                var long_date = weekday + ', ' + month_long + ' ' + day + ', ' + year;

                // Formatting dates for display.
                var formatted_start_date = start_date.toLocaleString('en-US', { timeZone: 'UTC', hour: 'numeric', minute: 'numeric', hour12: true });
                var event_start_date = formatted_start_date.replace(" AM", "am").replace(" PM","pm") + ' - ';
                var formatted_end_date = end_date.toLocaleString('en-US', { timeZone: 'UTC', hour: 'numeric', minute: 'numeric', hour12: true });
                var event_end_date = formatted_end_date.replace(" AM", "am").replace(" PM","pm");

                // We only show Edit link if user is logged in.
                if( $( "body.user-logged-in" ).length ) {
                  var edit_link = '<a href="/node/' + event.nid + '/edit">edit</a>';
                } else {
                  var edit_link = '';
                }

                // Markup for Events - List view
                if (view.name == 'listMonth') {
                    element.html('<div class="event-wrapper"><div class="date-square-wrapper"><div class="date-square">\n' +
                        '<div class="month">' + month_short + '</div>\n' + '<div class="day">' + day
                        + '</div></div></div></div>\n' + '<div class="event-details-wrapper">' + '<a href="/node/' + event.nid + '">'
                        + event.title + '</a><br />' + long_date + '&nbsp;' + event_start_date + event_end_date
                        + '<br />' + '<br />' + edit_link + '</div><div class="event-details-category">' + event.type
                        + '</div><div class="event-details-links"><a class="details" href="/node/' + event.nid + '">See Details</a>'
                        + '</div></div>');
                }
                // Markup for Events - All other views
                else {
                    element.html('<a href="/node/' + event.nid + '">' + event.title + '</a><br />' + event_start_date +
                        event_end_date + '<br /><br />' + edit_link);
                }
                // Shows event if All or its option value is selected from filter.
                return (['0', event.event_type].indexOf($('#event_type').val()) >= 0);
            },
            viewRender: function () {
                buildEventMonthList();
            },
        });

        // Set initial jump list on load.
        buildEventMonthList();
        // Populate date filter select list.
        function buildEventMonthList() {
            $('#event_date').empty(); // clear jump list
            var month = $calendar.fullCalendar('getDate');
            var initial = month.format('YYYY-MM'); // where are we?
            month.add(-6, 'month'); // 6 months past
            for (var i = 0; i < 13; i++) { // 6 months future
                var opt = document.createElement('option');
                opt.value = month.format('YYYY-MM-01');
                opt.text = month.format('MMM YYYY');
                opt.selected = initial === month.format('YYYY-MM'); // current selection
                $date.append(opt);
                month.add(1, 'month');
            }
        }

        // ChangeView to List for smaller screens
        function recreateFC(screenWidth) {
          if (screenWidth < 885) {
           $('#calendar').fullCalendar('changeView', 'listMonth');
          }
        }

        $('.calendar-filters h3').click(function () {
          $('.filters-wrapper').toggleClass('open');
        });

        $(window).on('load resize', function () {
          recreateFC($(window).width());
        });

        // Change Filters Width on slect list change.
        $('.calendar-filters .form-select').change(function () {
            var $selectList = $(this);

            var $selectedOption = $selectList.children('[value="' + this.value + '"]')
                .attr('selected', true);
            var selectedIndex = $selectedOption.index();

            var $nonSelectedOptions = $selectList.children().not($selectedOption)
                .remove()
                .attr('selected', false);

            // Reset and calculate new fixed width having only selected option as a child
            $selectList.width('auto').width($selectList.width());

            // Add options back and put selected option in the right place on the list
            $selectedOption.remove();
            $selectList.append($nonSelectedOptions);
            if (selectedIndex >= $nonSelectedOptions.length) {
                 $selectList.append($selectedOption);
            } else {
                 $selectList.children().eq(selectedIndex).before($selectedOption);
            }
        });

        // Function for getting query parameters from URL.
        function getParameterByName(name) {
            if (name !== "" && name !== null && name != undefined) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                    results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            } else {
                var arr = location.href.split("/");
                return arr[arr.length - 1];
            }
        }

        // Apply filters based on query parameters in URL.
        $(window).on('load', function () {
            // If type query parameter is present, set select list its value.
            if (getParameterByName('type') != '') {
                let params = (new URL(window.location)).searchParams;
                let type = params.get("type");
                $('#event_type').val(type).find("option[value=" + type + "]").attr('selected', true).trigger('change');
            }
        });

    });
}(Drupal, jQuery));
