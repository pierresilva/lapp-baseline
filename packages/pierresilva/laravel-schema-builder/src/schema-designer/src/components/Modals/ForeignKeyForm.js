/**
 * @flow
 */
import React, { PureComponent } from 'react';
import find from 'lodash/find';
import type { ColumnType, ForeignKeyType, TableType } from '../../utils/flowtypes';

type Props = {
    tables: Array<TableType>,
    columns: {
        [tableId: string]: Array<ColumnType>
    },
    data: ForeignKeyType
};

type State = {
    currentForeignTableId: string,
    currentForeignTableName: string,
    currentForeignColumnId: string,
    currentForeignColumnName: string,
    currentRelationType: string,
    currentMethodName: string
};

class ForeignKeyForm extends PureComponent<Props, State> {
    constructor(props: Props) {
        super(props);

        const { data } = props;

        this.state = {
            currentForeignTableId: data.on.id,
            currentForeignTableName: data.on.name,
            currentForeignColumnId: data.references.id,
            currentForeignColumnName: data.references.name,
            currentRelationType: data.references.type,
            currentMethodName: data.references.method
        };
    }

    props: Props

    state: State

    getData = () => {
        const {
            currentForeignColumnId,
            currentForeignColumnName,
            currentForeignTableId,
            currentForeignTableName,
            currentRelationType,
            currentMethodName
        } = this.state;

        let invalidData = false;

        if (!currentForeignTableId || !currentForeignColumnId || !currentRelationType || !currentMethodName) {
            invalidData = true;
        }

        return {
            references: {
                id: invalidData ? '' : currentForeignColumnId,
                name: invalidData ? '' : currentForeignColumnName
            },
            on: {
                id: invalidData ? '' : currentForeignTableId,
                name: invalidData ? '' : currentForeignTableName
            },
            type: invalidData ? '' : currentRelationType,
            method: invalidData ? '' : currentMethodName
        };
    }

    setCurrentForeignTable = (event: { target: { value: string } }) => {
        const { tables } = this.props;

        const selected = event.target.value;
        let name = '';

        if (selected) {
            // eslint-disable-next-line
            name = find(tables, { id: selected }).name;
        }

        this.setState({
            currentForeignTableId: selected,
            currentForeignTableName: name,
            currentForeignColumnId: '',
            currentForeignColumnName: '',
            currentRelationType: '',
            currentMethodName: ''
        });
    }

    setCurrentForeignColumn = (event: { target: { value: string } }) => {
        const { columns } = this.props;
        const { currentForeignTableId } = this.state;

        const selected = event.target.value;
        let name = '';

        if (selected) {
            // eslint-disable-next-line
            name = find(columns[currentForeignTableId], { id: selected }).name;
        }

        this.setState({
            currentForeignColumnId: selected,
            currentForeignColumnName: name
        });
    }

    setCurrentRelationType = (event: { target: { value: string } }) => {
        // const { columns } = this.props;
        // const { currentRelationType } = this.state;

        const selected = event.target.value;
        let name = '';

        if (selected) {
            // eslint-disable-next-line
            name = event.target.value;
        }

        this.setState({
            // currentRelationType: selected,
            currentRelationType: name
        });
    }

    setCurrentMethodName = (event: { target: { value: string } }) => {

        this.setState({
            currentMethodName: event.target.value
        });
    }

    render() {
        console.log('ForeignKeyForm rendering'); // eslint-disable-line no-console
        const { tables, data, columns } = this.props;
        const { currentForeignTableId } = this.state;

        return (
            <div className='form-group'>
                <strong className='col-xs-3 control-label'>Foreign Key:</strong>
                <div className='col-xs-9 offset-3'>
                    <span className='col-xs-12 control-label'>On:</span>
                    <div className='col-xs-12'>
                        <select
                            className='form-control'
                            defaultValue={ data.on.id }
                            onChange={ this.setCurrentForeignTable }
                        >
                            <option value=''>None</option>
                            { tables.map((table) => (
                                <option key={ table.id } value={ table.id }>
                                    { table.name }
                                </option>
                            ))}
                        </select>
                    </div>
                </div>
                <div className='col-xs-9 offset-3'>
                    <span className='col-xs-12 control-label'>References:</span>
                    <div className='col-xs-12'>
                        <select
                            className='form-control'
                            defaultValue={ data.references.id }
                            onChange={ this.setCurrentForeignColumn }
                        >
                            <option value=''>None</option>

                            { columns[currentForeignTableId] !== undefined &&
                                columns[currentForeignTableId]
                                    .filter((column) => !column.foreignKey.on.id)
                                    .map((column) => (
                                        <option key={ column.id } value={ column.id }>
                                            { column.name }
                                        </option>
                                    ))
                            }
                        </select>
                    </div>
                </div>
                <div className='col-xs-9 offset-3'>
                    <span className='col-xs-12 control-label'>Type:</span>
                    <div className='col-xs-12'>
                        <select
                            className='form-control'
                            defaultValue={ data.type }
                            onChange={ this.setCurrentRelationType }
                        >
                            <option value=''>None</option>
                            <option value='belongsTo'>belongsTo</option>
                            <option value='belongsToMany'>belongsToMany</option>
                            <option value='hasOne'>hasOne</option>
                            <option value='hasMany'>hasMany</option>
                        </select>
                    </div>
                </div>
                <div className='col-xs-9 offset-3'>
                    <span className='col-xs-12 control-label'>Method:</span>
                    <div className='col-xs-12'>
                        <input
                            type='text'
                            id='method'
                            className='form-control'
                            defaultValue={ data.method }
                            onChange={ this.setCurrentMethodName }
                        />
                    </div>
                </div>
            </div>
        );
    }
}

export default ForeignKeyForm;
