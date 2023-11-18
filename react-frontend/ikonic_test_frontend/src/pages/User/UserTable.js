import React, { useEffect, useState } from 'react'
import DataTable from '../DataTable'
import { useDeleteUser, useUser } from '../../hooks/userQueries'
import { useNavigate } from 'react-router-dom';
import { Button, Stack, Typography } from '@mui/material';

const columns = [
    { field: 'name', headerName: 'Name', width: 130 },
    { field: 'email', headerName: 'Email', width: 130 },
];

const dropdown = [
    { label: 'Delete', action: "delete" },
    { label: 'Update', action: "update" },
];

function UserTable() {
    const adminQuery = useUser({});
    const adminDelete = useDeleteUser();
    const navigate = useNavigate();

    const [admin, setAdmins] = useState([]);
    useEffect(() => {
        if (adminQuery.isFetched && adminQuery.data) {
            setAdmins(adminQuery.data);
        }
    }, [adminQuery.data, adminQuery.isFetched, adminQuery.isFetching]);

    const handleAction = (params, action) => {
        if (action === 'delete') {
            adminDelete.mutate(params?.id);
        }
        if (action === "update") {
            console.log(">>>>>>>>>>paramsparams", params);
            navigate('/dashboard/user_form', {
                state: { detail: params?.row },
            });
        }
    }
    return (
        <>
            <DataTable columns={columns} admin={admin} dropdown={dropdown} handleAction={handleAction} listName="User List" navgatePath="/dashboard/user_form" buttonText="Add User " />
        </>
    )
}

export default UserTable