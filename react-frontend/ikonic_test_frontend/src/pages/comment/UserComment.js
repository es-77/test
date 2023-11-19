import * as React from 'react';
import Card from '@mui/material/Card';
import CardHeader from '@mui/material/CardHeader';
import CardContent from '@mui/material/CardContent';
import Avatar from '@mui/material/Avatar';
import Typography from '@mui/material/Typography';
import Pagination from '@mui/material/Pagination';
import { useState, useEffect } from 'react';

export default function UserComment({ comments }) {
    const [page, setPage] = useState(1);
    const [rowsPerPage] = useState(2);
    const [rowData, setRowData] = useState([]);

    useEffect(() => {
        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        setRowData(comments.slice(startIndex, endIndex));
    }, [comments, page, rowsPerPage]);

    const handlePageChange = (event, newPage) => {
        setPage(newPage);
    };
    useEffect(() => {
        setPage(1);
    }, [comments]);

    return (
        <>
            {rowData.map((row, index) => (
                <Card key={index} sx={{ maxWidth: 545, marginBottom: 7 }}>
                    <CardHeader
                        avatar={
                            <Avatar alt="Remy Sharp" src="https://i.pinimg.com/736x/60/1d/6e/601d6e42b4e15849969aa8b91ac8a4a6.jpg" />
                        }
                        title={`Comment By : ${row?.user?.name}`}
                        subheader={row?.created_at_human}
                    />
                    <CardContent>
                        <Typography variant="body2" color="text.secondary">
                            {row?.content}
                        </Typography>
                    </CardContent>
                </Card>
            ))}
            {comments.length > rowsPerPage && (
                <Pagination
                    count={Math.ceil(comments.length / rowsPerPage)}
                    page={page}
                    onChange={handlePageChange}
                />
            )}
        </>
    );
}
