import * as React from 'react';
import { styled } from '@mui/material/styles';
import Card from '@mui/material/Card';
import CardHeader from '@mui/material/CardHeader';
import CardMedia from '@mui/material/CardMedia';
import CardContent from '@mui/material/CardContent';
import CardActions from '@mui/material/CardActions';
import Collapse from '@mui/material/Collapse';
import Avatar from '@mui/material/Avatar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import { red } from '@mui/material/colors';
import FavoriteIcon from '@mui/icons-material/Favorite';
import ShareIcon from '@mui/icons-material/Share';
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';
import MoreVertIcon from '@mui/icons-material/MoreVert';
import { useFeedbackComment } from '../../hooks/feedbacCommentQueries';
import { useEffect, useState } from 'react';
import RichTextEditor from '../Editor/RichTextEditor';
import UserComment from './UserComment';

const ExpandMore = styled((props) => {
    const { expand, ...other } = props;
    return <IconButton {...other} />;
})(({ theme, expand }) => ({
    marginLeft: 'auto',
    transition: theme.transitions.create('transform', {
        duration: theme.transitions.duration.shortest,
    }),
}));

export default function GiveFeedBackOnComment() {
    const feedbackCommentQuery = useFeedbackComment();
    const itemsPerPage = 5; // Set the number of items to display per page

    const [expanded, setExpanded] = useState([]);
    const [admin, setAdmins] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);

    useEffect(() => {
        if (feedbackCommentQuery.isFetched && feedbackCommentQuery.data) {
            setAdmins(feedbackCommentQuery.data);
        }
    }, [feedbackCommentQuery.data, feedbackCommentQuery.isFetched, feedbackCommentQuery.isFetching]);

    const handleExpandClick = (index) => {
        setExpanded((prevExpanded) => {
            const newExpanded = [...prevExpanded];
            newExpanded[index] = !newExpanded[index];
            return newExpanded;
        });
    };

    const indexOfLastItem = currentPage * itemsPerPage;
    const indexOfFirstItem = indexOfLastItem - itemsPerPage;
    const currentItems = admin.slice(indexOfFirstItem, indexOfLastItem);

    const paginate = (pageNumber) => setCurrentPage(pageNumber);

    return (
        <>
            {currentItems.map((item, index) => {
                return (
                    <Card key={index} sx={{ maxWidth: 545, marginBottom: 7, mx: 'auto' }}>
                        <CardHeader
                            title={`Title : ${item?.title}`}
                            subheader={`Created At : ${item?.created_at_human}`}
                        />
                        <CardMedia
                            component="img"
                            height="100"
                            image="https://purepng.com/public/uploads/large/nature-3yp.png"
                            alt="Paella dish"
                        />
                        <CardContent>
                            <Typography variant="body2" color="text.secondary">
                                Description :  {item?.description}
                            </Typography>
                        </CardContent>
                        <CardActions disableSpacing>
                            <IconButton aria-label="add to favorites">
                                Vote Count : {item?.votes_count}
                            </IconButton>
                            <ExpandMore
                                expand={expanded[index]}
                                onClick={() => handleExpandClick(index)}
                                aria-expanded={expanded[index]}
                                aria-label="show more"
                            >
                                comments count : {item?.comments?.length}
                                <ExpandMoreIcon />
                            </ExpandMore>
                        </CardActions>
                        <Collapse in={expanded[index]} timeout="auto" unmountOnExit>
                            <CardContent>
                                <RichTextEditor feedbackId={item?.id} />
                                <Typography paragraph>Comments : </Typography>
                                <UserComment comments={item?.comments} />
                            </CardContent>
                        </Collapse>
                    </Card>
                );
            })}
            <Pagination
                itemsPerPage={itemsPerPage}
                totalItems={admin.length}
                paginate={paginate}
                currentPage={currentPage}
            />
        </>
    );
}

const Pagination = ({ itemsPerPage, totalItems, paginate, currentPage }) => {
    const pageNumbers = [];

    for (let i = 1; i <= Math.ceil(totalItems / itemsPerPage); i++) {
        pageNumbers.push(i);
    }

    return (
        <nav>
            <ul style={{ listStyle: 'none', display: 'flex', justifyContent: 'center' }}>
                {pageNumbers.map((number) => (
                    <li key={number} style={{ margin: '0 5px' }}>
                        <a
                            href="#"
                            onClick={() => paginate(number)}
                            style={{
                                textDecoration: 'none',
                                padding: '8px 16px',
                                backgroundColor: currentPage === number ? 'blue' : 'white',
                                color: currentPage === number ? 'white' : 'black',
                            }}
                        >
                            {number}
                        </a>
                    </li>
                ))}
            </ul>
        </nav>
    );
};
