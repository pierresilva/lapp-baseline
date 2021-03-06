/**
 * @flow
 */

export type ForeignKeyType = {
    references: {
        id: string,
        name: string
    },
    on: {
        id: string,
        name: string
    },
    type: string,
    method: string

};

export type ColumnType = {
    id: string,
    name: string,
    label: string,
    type: string,
    comment: string,
    autoInc: boolean,
    unique: boolean,
    index: boolean,
    unsigned: boolean,
    nullable: boolean,
    length: string,
    defValue: string,
    validation: string,
    foreignKey: ForeignKeyType
};

export type TableType = {
    id: string,
    name: string,
    comment: string,
    color: string,
    logActivities: boolean,
    softDelete: boolean,
    timeStamp: boolean,
    status: boolean
};

export type RelationType = {
    source: {
        tableId: string,
        columnId: string
    },
    target: {
        tableId: string,
        columnId: string
    },
    data: {
        label: string
    }
};

export type TablePositionType = {
    id: string,
    x: number,
    y: number
};

export type UiType = {
    database: {
        showModal: boolean,
        edit: boolean
    },
    table: {
        showModal: boolean,
        edit: false,
        editData: TableType
    },
    column: {
        showModal: boolean,
        edit: false,
        editData: ColumnType
    }
};
