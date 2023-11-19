import React, { useEffect, useState } from 'react'
import DataTable from '../DataTable';
import { useComment, useDeleteComment, useUpdateComment } from '../../hooks/commentQueries';
import RichTextEditor from '../Editor/RichTextEditor';
import { toast } from 'react-toastify';

const columns = [
    { field: 'userName', headerName: 'User Name', width: 130 },
    { field: 'userEmail', headerName: 'User Email', width: 130 },
    { field: 'feedBackTitle', headerName: 'Feed Back Title', width: 130 },
    { field: 'category', headerName: 'Category', width: 130 },
    { field: 'content', headerName: 'Content', width: 130 },
    { field: 'display', headerName: 'Display', width: 130 },
];

const dropdown = [
    { label: 'Delete', action: "delete" },
    { label: 'Update', action: "update" },
    { label: 'Approved Comment', action: "approved" },
];

function CommentTable() {
    const commentQuery = useComment({});
    const commentUpdateQuery = useUpdateComment({});
    const commentdeleteQuery = useDeleteComment();
    const [admin, setAdmins] = useState([]);
    useEffect(() => {
        if (commentQuery.isFetched && commentQuery.data) {
            setAdmins(commentQuery.data);
        }
    }, [commentQuery.data, commentQuery.isFetched, commentQuery.isFetching]);

    const handleAction = (params, action) => {
        if (action === 'delete') {
            commentdeleteQuery.mutate(params?.id);
        }
        if (action === "approved") {
            const commentData = {
                commentId: params?.id,
                "tab": "updateDisplay"
            }
            commentUpdateQuery.mutate(commentData);
        }
    }

    useEffect(() => {
        const data = commentUpdateQuery?.data;
        if (commentUpdateQuery.isSuccess) {
            const message = data?.message;
            const validationErrors = data?.validation_errors;
            const response = data?.response;
            if (response === 101) {
                toast.success(message);
            } else {
                Object.keys(validationErrors).forEach((key) => {
                    toast.error(validationErrors[key][0]);
                });
            }
        }

        if (commentUpdateQuery.isError) {
            const message = 'Error occurred while saving the data';
            toast.error(message);
        }
    }, [commentUpdateQuery.isSuccess, commentUpdateQuery.isError]);
    return (
        <div>
            <DataTable columns={columns} admin={admin} dropdown={dropdown} handleAction={handleAction} listName="Comment List" navgatePath="/dashboard/feedback_form" buttonText="Add Comment" />
        </div>
    )
}

export default CommentTable