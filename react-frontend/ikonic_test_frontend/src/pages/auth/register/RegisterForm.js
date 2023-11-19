import * as Yup from 'yup';
import { useState } from 'react';
import { useFormik, Form, FormikProvider } from 'formik';
import { Link as RouterLink, useNavigate } from 'react-router-dom';
// material
import { Link, Stack, TextField, IconButton, InputAdornment, Button, Container } from '@mui/material';
import { AuthContext } from '../../../context/AuthContext';
import { useContext } from 'react';
import { useEffect } from 'react';
import { useRegistor } from '../../../hooks/api/authQueries';
import { toast } from 'react-toastify';


// ----------------------------------------------------------------------

export default function RegisterForm() {
    const navigate = useNavigate();

    const { loginUser } = useContext(AuthContext);

    const registorQuery = useRegistor();

    const [showPassword, setShowPassword] = useState(false);

    const RegisterSchema = Yup.object().shape({
        firstName: Yup.string().min(2, 'Too Short!').max(50, 'Too Long!').required('First name required'),
        email: Yup.string().email('Email must be a valid email address').required('Email is required'),
        password: Yup.string().required('Password is required'),
    });

    const formik = useFormik({
        initialValues: {
            firstName: '',
            email: '',
            password: '',
        },
        validationSchema: RegisterSchema,
        onSubmit: (data) => {
            registorQuery.mutate(data);
        },
    });

    useEffect(() => {
        if (registorQuery.isSuccess && registorQuery.data) {
            const { user, token } = registorQuery.data.data;
            loginUser(user, token);
            navigate('/dashboard/give_feedback_on_comment', { replace: true });
        }

        if (registorQuery.isError) {
            const message = registorQuery.error.response?.data.message;
            toast.error(message);
        }
    }, [registorQuery.data, registorQuery.isError])


    const { errors, touched, handleSubmit, isSubmitting, getFieldProps } = formik;

    return (
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
                        <Stack
                            direction="row"
                            justifyContent="space-between"
                            alignItems="center"
                            spacing={2}
                            margin={2}
                        >
                            <Link component={RouterLink} variant="subtitle2" to="/login" underline="hover">
                                Alread have Account
                            </Link>
                        </Stack>

                        <Button fullWidth size="large" type="submit" variant="contained" loading={isSubmitting}>
                            Register
                        </Button>
                    </Stack>
                </Form>
            </FormikProvider>
        </Container>
    );
}
