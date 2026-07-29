var TableManaged = function () {

    var initTable1 = function () {

        var table = $('#sample_1');

        // begin first table
        table.dataTable({
            
            "lengthMenu": [
                [10, 50, 100, -1],
                [10, 50, 100, "Todos"] // change per page values here
            ],
            // set the initial value
            "pageLength": 50,            
            "pagingType": "bootstrap_full_number",
            "language": {
				processing:     "Processando...",
				search:         "Buscar",
				lengthMenu:    "Mostrar _MENU_ registros",
				info:           "Mostrando registros de _START_ a _END_ de um total de _TOTAL_ registros",
				infoEmpty:      "Nenhum registro encontrado",
				infoFiltered:   "(filtrado de um total de _MAX_ registros)",
				infoPostFix:    "",
				loadingRecords: "Carregando informações...",
				zeroRecords:    "Nenhum registro encontrado",
				emptyTable:     "Ainda não há nenhum registro.",
				paginate: {
					first:      "Primeira",
					previous:   "Anterior",
					next:       "Próxima",
					last:       "Última"
				},
				aria: {
					sortAscending:  ": Ative a coluna para ordená-la em ordem crescente",
					sortDescending: ": Ative a coluna para ordená-la em ordem crescente"
				}
			},
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "order": [
                [2, "asc"]
            ] // set first column as a default sort by asc
        });

        var tableWrapper = jQuery('#sample_1_wrapper');

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).attr("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            jQuery.uniform.update(set);
        });

        table.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
        });

        tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
    }

    
    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            initTable1();
        }

    };

}();