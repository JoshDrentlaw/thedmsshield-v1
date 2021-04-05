$(document).ready(function () {
    const deleteMenuItem = {
        label: 'Delete',
        action: function (e, column) {
            const field = column._column.field

            column.delete().then(function () {
                newRandomTableRows = newRandomTableRows.map(r => {
                    let temp = {}
                    for (let k in r) {
                        if (k !== field) {
                            temp[k] = r[k]
                        }
                    }
                    return temp
                })
                newRandomTable.redraw()
            })
        }
    },
        renameColumn = {
            label: 'Rename Column',
            action: function (e, column) {
                column.updateDefinition({ editableTitle: true, movable: false }).then(function (column) {
                    console.log(column._column.definition)
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

    $(document).on('click', '#add-table-column', function () {
        addRandomTableColumn()
    })

    $(document).on('click', '#add-table-row', function () {
        addRandomTableRow()
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

    function addRandomTableColumn() {
        const newNum = newRandomTableColumnCount++,
            newColumn = `Column ${newNum}`,
            cols = newRandomTable.getColumns()

        newRandomTableRows = newRandomTableRows.map(r => {
            r[newColumn] = 'Enter result info'
            return r
        })
        newRandomTable.addColumn({ title: newColumn, field: newColumn, editor: 'textarea', editableTitle: true, movable: true, headerMenu: [renameColumn, deleteMenuItem] }, true, cols[cols.length - 1])
        newRandomTable.redraw()
    }

    function addRandomTableRow() {
        let row = {}

        for (let k in newRandomTableRows[0]) {
            if (k === 'targetNumber') {
                row[k] = getNextTargetNumber()
            } else {
                row[k] = 'Enter result info'
            }
        }
        newRandomTableRows.push(row)
        newRandomTable.redraw()
    }

    function getNextTargetNumber() {
        const col = newRandomTable.getColumn('targetNumber')
        let nums = col._column.cells.map(c => c.value),
            nextTargetNumber = 1

        while (nums.includes(nextTargetNumber)) {
            nextTargetNumber++
        }

        return nextTargetNumber
    }

    function resequenceTargetNumbers() {
        newRandomTableRows.forEach((r, i) => r.targetNumber = (i + 1))
    }

    let currentRandomTable

    $(document).on('show.bs.modal', '#show-random-table-modal', function (e) {
        axios.get(`/randomTables/${$(e.relatedTarget).data('id')}`)
            .then(res => {
                if (res.status === 200) {
                    const data = JSON.parse(res.data.table_data),
                        columns = []

                    for (let k in data[0]) {
                        if (k !== 'targetNumber') {
                            columns.push({ title: k, field: k })
                        }
                    }
                    currentRandomTable = new Tabulator('#show-random-table', {
                        minHeight: '200px',
                        maxHeight: '400px',
                        data,
                        columns: [
                            { title: '#', field: 'targetNumber', width: 50, maxWidth: 100 },
                            ...columns
                        ],
                        responsiveLayout: true,
                        reactiveData: true,
                        index: 'targetNumber',
                        layout: 'fitColumns',
                        layoutColumnsOnNewData: true
                    })
                }
            })
    })

    $(document).on('click', '#get-random-table-result', function () {
        const rowCount = currentRandomTable.getDataCount(),
            result = (Math.floor(Math.random() * rowCount)) + 1,
            row = currentRandomTable.getRow(result)

        $('.result-row').removeAttr('style')
        $(row.getElement()).addClass('result-row').css('background', 'lightblue')
    })
})