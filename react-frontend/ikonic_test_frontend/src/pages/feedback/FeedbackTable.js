import React, { useEffect, useState } from 'react'
import DataTable from '../DataTable';
import { useDeleteFeedback, useFeedback } from '../../hooks/feedbackQueries';
import { useNavigate } from 'react-router-dom';

const columns = [
    { field: 'title', headerName: 'Title', width: 130 },
    { field: 'description', headerName: 'Description', width: 130 },
    { field: 'category', headerName: 'Category', width: 130 },
    { field: 'votes_count', headerName: 'Votes Count ', width: 130 },
    { field: 'comments_count', headerName: 'Comments Count ', width: 130 },
];

const dropdown = [
    { label: 'Delete', action: "delete" },
    { label: 'Update', action: "update" },
];

function FeedbackTable() {
    const adminQuery = useFeedback({});
    const feedbackDeleteQuery = useDeleteFeedback({});
    const navigate = useNavigate();
    const [admin, setAdmins] = useState([]);
    useEffect(() => {
        if (adminQuery.isFetched && adminQuery.data) {
            setAdmins(adminQuery.data);
        }
    }, [adminQuery.data, adminQuery.isFetched, adminQuery.isFetching]);

    const handleAction = (params, action) => {
        if (action === 'delete') {
            feedbackDeleteQuery.mutate(params?.id);
        }
        if (action === "update") {
            console.log(">>>>>>>>>>paramsparams", params);
            navigate('/dashboard/feedback_form', {
                state: { detail: params?.row },
            });
        }
    }
    return (
        <div>
            <DataTable columns={columns} admin={admin} dropdown={dropdown} handleAction={handleAction} listName="Feed Back List" navgatePath="/dashboard/feedback_form" buttonText="Add Feed Back" />
        </div>
    )
}

export default FeedbackTable