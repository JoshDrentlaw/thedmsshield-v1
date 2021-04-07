$(document).ready(function () {
    const deleteMenuItem = {
        label: 'Delete',
        action: function (e, column) {
            const field = column._column.field

            let rows, table

            if (newRandomTable) {
                rows = newRandomTableRows
                table = newRandomTable
            } else if (currentRandomTable) {
                rows = currentRandomTableRows
                table = currentRandomTable
            }

            column.delete().then(function () {
                rows = rows.map(r => {
                    let temp = {}
                    for (let k in r) {
                        if (k !== field) {
                            temp[k] = r[k]
                        }
                    }
                    return temp
                })
                table.redraw()
                if (currentRandomTable) {
                    updateCurrentRandomTable()
                }
            })
        }
    },
        renameColumn = {
            label: 'Rename Column',
            action: function (e, column) {
                column.updateDefinition({ editableTitle: true, movable: false }).then(function (column) {
                    console.log(column)
                    $('.tabulator-title-editor:visible').trigger('focus')
                    $('.tabulator-title-editor:visible')[0].select()
                })
            }
        },
        deleteRow = function (e, cell) {
            let row = cell._cell.row,
                pos = newRandomTable.getRowPosition(row)

            row.delete().then(function () {
                newRandomTableRows = newRandomTableRows.filter((r, i) => i !== pos)
                resequenceTargetNumbers()
                newRandomTable.redraw()
            })
        }

    let newRandomTable,
        newRandomTableRows,
        newRandomTableColumnCount

    $(document).on('show.bs.modal', '#new-random-table-modal', function () {
        console.log('new random table modal')
        initRandomTableVars()
        initNewRandomTable()
    })

    $(document).on('click', '.add-table-column', function () {
        if (newRandomTable) {
            addRandomTableColumn(newRandomTable, newRandomTableRows, newRandomTableColumnCount)
        } else if (currentRandomTable) {
            addRandomTableColumn(currentRandomTable, currentRandomTableRows, currentRandomTableNewColumnCount)
            updateCurrentRandomTable()
        }
    })

    $(document).on('click', '.add-table-row', function () {
        if (newRandomTable) {
            addRandomTableRow(newRandomTable, newRandomTableRows)
        } else if (currentRandomTable) {
            addRandomTableRow(currentRandomTable, currentRandomTableRows)
            updateCurrentRandomTable()
        }
    })

    $(document).on('click', '#new-random-table-submit', function () {
        if ($('#random-table-name').val() === '') {
            pnotify.error({ title: 'Please enter a name for your new random table.' })
            return
        }
        const data = JSON.stringify(newRandomTable.getData()),
            name = $('#random-table-name').val()

        axios.post('/randomTables', { name, data, campaignId: campaign.id })
            .then(res => {
                if (res.status === 200) {
                    newRandomTable = undefined
                    newRandomTableColumnCount = 0
                    const tables = JSON.parse(res.data.random_tables)
                    $('#new-random-table-modal').modal('hide')
                    $('#random-table-list').children().remove()
                    tables.forEach(t => {
                        $('#random-table-list').append(`
                            <li class="list-group-item list-group-item-action show-random-table" data-id="${t.id}" data-toggle="modal" data-target="#show-random-table-modal">
                                ${t.name}
                            </li>
                        `)
                    })
                }
            })
            /* .catch(error => {
                pnotify.error({ title: 'Table name must be unique to this campaign.' })
            }) */
    })

    function initRandomTableVars() {
        newRandomTableRows = [
            {targetNumber: 1, "Column 0": 'Enter result info'},
            {targetNumber: 2, "Column 0": 'Enter result info'}
        ]
        newRandomTableColumnCount = 1
    }

    function initNewRandomTable() {
        newRandomTable = new Tabulator('#new-random-table', {
            minHeight: '200px',
            maxHeight: '400px',
            data: newRandomTableRows,
            columns: [
                { rowHandle:true, formatter:"handle", movable: false, headerSort:false, frozen:true, width:35, minWidth:35 },
                { title: '#', field: 'targetNumber', movable: false, width: 50, maxWidth: 100 },
                { title: 'Column 0', field: 'Column 0', editor: 'textarea', editableTitle: false, movable: true, headerMenu: [renameColumn, deleteMenuItem] },
                { formatter:"buttonCross", movable: false, headerSort:false, frozen:true, width:45, minWidth:45, hozAlign:'center', cellClick: deleteRow }
            ],
            movableColumns: true,
            movableRows: true,
            responsiveLayout: true,
            reactiveData: true,
            layout: 'fitColumns',
            layoutColumnsOnNewData: true,
            columnTitleChanged: function (column) {
                const oldField = column._column.definition.field,
                    newField = column._column.definition.title

                column.updateDefinition({ editableTitle: false, movable: true, field: newField }).then(function () {
                    newRandomTableRows = newRandomTableRows.map(r => {
                        r[newField] = r[oldField]
                        delete r[oldField]
                        console.log(r)

                        return r
                    })
                    newRandomTable.setData(newRandomTableRows)
                })
            },
            columnMoved: function (column, columns) {
                newRandomTableRows = newRandomTable.getData()
                newRandomTable.setData(newRandomTableRows).then(function () {
                    newRandomTable.redraw()
                })
            },
            rowMoved: function (row) {
                newRandomTableRows = newRandomTable.getData()
                resequenceTargetNumbers()
                newRandomTable.setData(newRandomTableRows).then(function () {
                    newRandomTable.redraw()
                })
            }
        })
    }

    function addRandomTableColumn(table, rows, count) {
        const newColumn = `Column ${count++}`,
            cols = table.getColumns()

        rows = rows.map(r => {
            r[newColumn] = 'Enter result info'
            return r
        })
        table.addColumn({ title: newColumn, field: newColumn, editor: 'textarea', editableTitle: true, movable: true, headerMenu: [renameColumn, deleteMenuItem] }/* , true, cols[cols.length - 1] */)
        table.redraw()
    }

    function addRandomTableRow(table, rows) {
        let row = {}

        for (let k in rows[0]) {
            if (k === 'targetNumber') {
                row[k] = getNextTargetNumber()
            } else {
                row[k] = 'Enter result info'
            }
        }
        rows.push(row)
        table.redraw()
    }

    function getNextTargetNumber() {
        let col
        if (newRandomTable) {
            col = newRandomTable.getColumn('targetNumber')
        } else if (currentRandomTable) {
            col = currentRandomTable.getColumn('targetNumber')
        }

        let nums = col._column.cells.map(c => c.value),
            nextTargetNumber = 1 

        while (nums.includes(nextTargetNumber)) {
            nextTargetNumber++
        }

        return nextTargetNumber
    }

    function resequenceTargetNumbers() {
        if (newRandomTable) {
            newRandomTableRows.forEach((r, i) => r.targetNumber = (i + 1))
        } else if (currentRandomTable) {
            currentRandomTableRows.forEach((r, i) => r.targetNumber = (i + 1))
        }
    }

    let currentRandomTable,
        currentRandomTableRows,
        currentRandomTableNewColumnCount = 0

    $(document).on('show.bs.modal', '#show-random-table-modal', function (e) {
        axios.get(`/randomTables/${$(e.relatedTarget).data('id')}`)
            .then(res => {
                if (res.status === 200) {
                    const columns = []

                    currentRandomTableRows = JSON.parse(res.data.table_data)

                    for (let k in currentRandomTableRows[0]) {
                        if (k !== 'targetNumber') {
                            columns.push({ title: k, field: k, editor: 'input', editableTitle: false, movable: true, headerMenu: [renameColumn, deleteMenuItem] })
                        }
                    }

                    $('#random-table-name-header').text(res.data.name)
                    $('#current-random-table-id').val(res.data.id)
                    currentRandomTable = new Tabulator('#show-random-table', {
                        minHeight: '200px',
                        maxHeight: '400px',
                        data: currentRandomTableRows,
                        columns: [
                            { rowHandle:true, formatter:"handle", movable: false, headerSort:false, frozen:true, width:35, minWidth:35 },
                            { title: '#', field: 'targetNumber', width: 70, maxWidth: 100 },
                            ...columns
                        ],
                        movableColumns: true,
                        movableRows: true,
                        responsiveLayout: true,
                        reactiveData: true,
                        index: 'targetNumber',
                        layout: 'fitColumns',
                        layoutColumnsOnNewData: true,
                        columnTitleChanged: function (column) {
                            const oldField = column._column.definition.field,
                                newField = column._column.definition.title

                            column.updateDefinition({ editableTitle: false, movable: true, field: newField }).then(function () {
                                currentRandomTableRows = currentRandomTableRows.map(r => {
                                    r[newField] = r[oldField]
                                    delete r[oldField]
                                    console.log(r)

                                    return r
                                })
                                currentRandomTable.setData(currentRandomTableRows)
                                    .then(function () {
                                        updateCurrentRandomTable()
                                    })
                            })
                        },
                        columnMoved: function (column, columns) {
                            currentRandomTableRows = currentRandomTable.getData()
                            currentRandomTable.setData(currentRandomTableRows).then(function () {
                                currentRandomTable.redraw()
                                updateCurrentRandomTable()
                            })
                        },
                        rowMoved: function (row) {
                            currentRandomTableRows = currentRandomTable.getData()
                            resequenceTargetNumbers()
                            currentRandomTable.setData(currentRandomTableRows).then(function () {
                                currentRandomTable.redraw()
                                updateCurrentRandomTable()
                            })
                        }
                    })
                }
            })
    })

    $(document).on('hidden.bs.modal', '#show-random-table-modal', function (e) {
        currentRandomTable = undefined
        currentRandomTableRows = []
        currentRandomTableNewColumnCount = 0
    })

    $(document).on('click', '#get-random-table-result', function () {
        const rowCount = currentRandomTable.getDataCount(),
            result = (Math.floor(Math.random() * rowCount)) + 1,
            row = currentRandomTable.getRow(result)

        $('.result-row').removeAttr('style')
        $(row.getElement()).addClass('result-row').css('background', 'lightblue')
    })

    function updateCurrentRandomTable() {
        const table_data = JSON.stringify(currentRandomTable.getData())

        axios.put(
            `/randomTables/${$('#current-random-table-id').val()}`,
            {table_data}
        )
    }
})