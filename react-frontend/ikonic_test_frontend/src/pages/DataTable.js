import * as React from 'react';
import { DataGrid } from '@mui/x-data-grid';
import IconMenu from './IconMenu.jsx';
import { useEffect } from 'react';
import { useState } from 'react';
import { Button, Stack, Typography } from '@mui/material';
import { useNavigate } from 'react-router-dom';

export default function DataTable({ columns, admin, dropdown, handleAction, listName, navgatePath, buttonText }) {
    const navigate = useNavigate();
    const [columnsData, SetColumnsData] = useState([])
    const [rowData, setRowData] = useState([]);
    useEffect(() => {
        SetColumnsData(columns)
        setRowData(admin)
    }, [columns, admin, dropdown])

    const columnsWithActions = [...columnsData, {
        field: 'actions',
        headerName: 'Actions',
        width: 120,
        renderCell: (params) => (
            <div>
                <IconMenu handleAction={handleAction} dropdowlist={dropdown} params={params} />
            </div>
        ),
    }];

    return (
        <div style={{ height: 400, width: '100%' }}>
            <Stack
                direction="row"
                justifyContent="space-between"
                alignItems="center"
                spacing={12}
                margin={3}
            >
                <Typography variant="body2" color="initial">
                    {listName ?? ""}
                </Typography>
                <Button variant="contained" onClick={() => navgatePath ? navigate(navgatePath) : ""}> {buttonText ?? ""}</Button>
            </Stack>
            <DataGrid
                rows={rowData}
                columns={columnsWithActions}
                pageSize={5}
            />
        </div>
    );
}
