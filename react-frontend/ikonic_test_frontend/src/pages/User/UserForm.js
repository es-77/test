import * as Yup from 'yup';
import { useEffect, useState } from 'react';
import { useFormik, Form, FormikProvider } from 'formik';
import { useLocation, useNavigate } from 'react-router-dom';
// material
import { Stack, TextField, IconButton, InputAdornment, Button, Container } from '@mui/material';
import { useSaveUser, useUpdateUser } from '../../hooks/userQueries';
import { toast } from 'react-toastify';

function UserForm() {
    const navigate = useNavigate();
    const userQuery = useSaveUser();
    const userUpdateQuery = useUpdateUser();

    const location = useLocation();

    const [showPassword, setShowPassword] = useState(false);
    const [selectUserData] = useState(location?.state?.detail);

    const RegisterSchema = Yup.object().shape({
        firstName: Yup.string().min(2, 'Too Short!').max(50, 'Too Long!').required('First name required'),
        email: Yup.string().email('Email must be a valid email address').required('Email is required'),
        password: (selectUserData?.id) ? Yup.string() : Yup.string().required('Password is required'),
    });

    const formik = useFormik({
        initialValues: {
            userId: selectUserData?.id || '',
            firstName: selectUserData?.name || '',
            email: selectUserData?.email || '',
            password: '',
        },
        validationSchema: RegisterSchema,
        onSubmit: (data) => {
            if (selectUserData?.id) {
                userUpdateQuery.mutate(data);
            } else {
                userQuery.mutate(data);
            }
        },
    });


    useEffect(() => {
        const data = userQuery?.data;
        if (userQuery.isSuccess) {
            const message = data?.message;
            const validationErrors = data?.validation_errors;
            const response = data?.response;
            if (response === 101) {
                toast.success(message);
                setTimeout(() => {
                    navigate('/dashboard/user', { replace: true });
                }, 2000);
            } else {
                Object.keys(validationErrors).forEach((key) => {
                    toast.error(validationErrors[key][0]);
                });
            }
        }

        if (userQuery.isError) {
            const message = 'Error occurred while saving the data';
            toast.error(message);
        }
    }, [userQuery.isSuccess, userQuery.isError]);

    useEffect(() => {
        const data = userUpdateQuery?.data;
        if (userUpdateQuery.isSuccess) {
            const message = data?.message;
            const validationErrors = data?.validation_errors;
            const response = data?.response;
            if (response === 101) {
                toast.success(message);
                setTimeout(() => {
                    navigate('/dashboard/user', { replace: true });
                }, 2000);
            } else {
                Object.keys(validationErrors).forEach((key) => {
                    toast.error(validationErrors[key][0]);
                });
            }
        }

        if (userUpdateQuery.isError) {
            const message = 'Error occurred while saving the data';
            toast.error(message);
        }
    }, [userUpdateQuery.isSuccess, userUpdateQuery.isError]);


    const { errors, touched, handleSubmit, isSubmitting, getFieldProps } = formik;

    return (
        <div>
            <Container maxWidth="sm">
                <FormikProvider value={formik}>
                    <Form autoComplete="off" noValidate onSubmit={handleSubmit}>
                        <Stack spacing={3}>
                            <TextField
                                fullWidth
                                label="First name"
                                {...getFieldProps('firstName')}
                                error={Boolean(touched.firstName && errors.firstName)}
                                helperText={touched.firstName && errors.firstName}
                            />

                            <TextField
                                fullWidth
                                autoComplete="username"
                                type="email"
                                label="Email address"
                                {...getFieldProps('email')}
                                error={Boolean(touched.email && errors.email)}
                                helperText={touched.email && errors.email}
                            />

                            <TextField
                                fullWidth
                                autoComplete="current-password"
                                type={showPassword ? 'text' : 'password'}
                                label="Password"
                                {...getFieldProps('password')}
                                InputProps={{
                                    endAdornment: (
                                        <InputAdornment position="end">
                                            <IconButton edge="end" onClick={() => setShowPassword((prev) => !prev)}>
                                                {/* <Iconify icon={showPassword ? 'eva:eye-fill' : 'eva:eye-off-fill'} /> */}
                                            </IconButton>
                                        </InputAdornment>
                                    ),
                                }}
                                error={Boolean(touched.password && errors.password)}
                                helperText={touched.password && errors.password}
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

export default UserForm