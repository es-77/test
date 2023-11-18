import * as Yup from 'yup';
import { useEffect, useState } from 'react';
import { useFormik, Form, FormikProvider } from 'formik';
import { useLocation, useNavigate } from 'react-router-dom';
// material
import { Stack, TextField, IconButton, InputAdornment, Button, Container, FormControl, InputLabel, NativeSelect, Input } from '@mui/material';
import { toast } from 'react-toastify';
import { useSaveFeedback, useUpdateFeedback } from '../../hooks/feedbackQueries';

function FeedbackForm() {
    const navigate = useNavigate();
    const feedbackQuery = useSaveFeedback();
    const feedbackUpdateQuery = useUpdateFeedback();

    const location = useLocation();

    const [selectUserData] = useState(location?.state?.detail);

    const RegisterSchema = Yup.object().shape({
        title: Yup.string().min(2, 'Too Short!').max(50, 'Too Long!').required('Title required'),
        description: Yup.string().required('Description is required'),
        // category: Yup.string().required('Category is required'),
    });

    const formik = useFormik({
        initialValues: {
            feedbackId: selectUserData?.id || '',
            title: selectUserData?.title || '',
            description: selectUserData?.description || '',
            category: selectUserData?.category || '',
        },
        validationSchema: RegisterSchema,
        onSubmit: (data) => {
            if (selectUserData?.id) {
                feedbackUpdateQuery.mutate(data, selectUserData?.id);
            } else {
                feedbackQuery.mutate(data);
            }
        },
    });


    useEffect(() => {
        const data = feedbackQuery?.data;
        if (feedbackQuery.isSuccess) {
            const message = data?.message;
            const validationErrors = data?.validation_errors;
            const response = data?.response;
            if (response === 101) {
                toast.success(message);
                setTimeout(() => {
                    navigate('/dashboard/feedback', { replace: true });
                }, 2000);
            } else {
                Object.keys(validationErrors).forEach((key) => {
                    toast.error(validationErrors[key][0]);
                });
            }
        }

        if (feedbackQuery.isError) {
            const message = 'Error occurred while saving the data';
            toast.error(message);
        }
    }, [feedbackQuery.isSuccess, feedbackQuery.isError]);

    useEffect(() => {
        const data = feedbackUpdateQuery?.data;
        if (feedbackUpdateQuery.isSuccess) {
            const message = data?.message;
            const validationErrors = data?.validation_errors;
            const response = data?.response;
            if (response === 101) {
                toast.success(message);
                setTimeout(() => {
                    navigate('/dashboard/feedback', { replace: true });
                }, 2000);
            } else {
                Object.keys(validationErrors).forEach((key) => {
                    toast.error(validationErrors[key][0]);
                });
            }
        }

        if (feedbackUpdateQuery.isError) {
            const message = 'Error occurred while saving the data';
            toast.error(message);
        }
    }, [feedbackUpdateQuery.isSuccess, feedbackUpdateQuery.isError]);


    const { errors, touched, handleSubmit, isSubmitting, getFieldProps } = formik;

    return (
        <div>
            <Container maxWidth="sm">
                <FormikProvider value={formik}>
                    <Form autoComplete="off" noValidate onSubmit={handleSubmit}>
                        <Stack spacing={3}>
                            <TextField
                                fullWidth
                                label="Title"
                                {...getFieldProps('title')}
                                error={Boolean(touched.title && errors.title)}
                                helperText={touched.title && errors.title}
                            />
                            <FormControl fullWidth>
                                <InputLabel variant="standard" htmlFor="uncontrolled-native">
                                    Category
                                </InputLabel>
                                <NativeSelect
                                    defaultValue={selectUserData?.category}
                                    name='category'
                                    id='category'
                                    {...getFieldProps('category')}
                                >
                                    <option value={'improvement'}>improvement</option>
                                    <option value={'feature request'}>feature request</option>
                                    <option value={'bug report'}>bug report</option>
                                </NativeSelect>
                            </FormControl>

                            <TextField
                                fullWidth
                                multiline
                                label="Description"
                                {...getFieldProps('description')}
                                error={Boolean(touched.description && errors.description)}
                                helperText={touched.description && errors.description}
                            />
                            <Button fullWidth size="large" type="submit" variant="contained" loading={isSubmitting}>
                                {selectUserData?.id ? "Update" : "Save"}
                            </Button>
                        </Stack>
                    </Form>
                </FormikProvider>
            </Container>
        </div>
    )
}

export default FeedbackForm