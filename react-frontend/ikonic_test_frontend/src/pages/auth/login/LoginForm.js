import * as Yup from 'yup';
import { useEffect, useState, useContext } from 'react';
import { Link as RouterLink, useNavigate } from 'react-router-dom';
import { useFormik, Form, FormikProvider } from 'formik';
// material
import { Link, Stack, TextField, Button, Container, InputAdornment, IconButton } from '@mui/material';
// component
import { toast } from 'react-toastify';
import { useLogin } from '../../../hooks/api/authQueries';
import { AuthContext } from '../../../context/AuthContext';


export default function LoginForm() {
    const navigate = useNavigate();
    const { loginUser } = useContext(AuthContext);

    const [showPassword, setShowPassword] = useState(false);

    const loginQuery = useLogin();

    const LoginSchema = Yup.object().shape({
        email: Yup.string().email('Email must be a valid email address').required('Email is required'),
        password: Yup.string().required('Password is required'),
    });

    const formik = useFormik({
        initialValues: {
            email: '',
            password: '',
            remember: true,
        },
        validationSchema: LoginSchema,
        onSubmit: (data) => {
            loginQuery.mutate(data);
        },
    });


    const { errors, touched, values, isSubmitting, handleSubmit, getFieldProps, setSubmitting } = formik;

    const handleShowPassword = () => {
        setShowPassword((show) => !show);
    };

    useEffect(() => {
        if (loginQuery.isSuccess && loginQuery.data) {
            const { user, token } = loginQuery.data.data;
            loginUser(user, token);
            navigate('/dashboard/give_feedback_on_comment', { replace: true });
        }

        if (loginQuery.isError) {
            setSubmitting(false);
            const message = loginQuery.error.response?.data.message;
            toast.error(message);
        }
    }, [loginQuery.data, loginQuery.isError])

    return (
        <Container maxWidth="sm" sx={{ marginTop: 5 }}>
            <FormikProvider value={formik}>
                <Form autoComplete="off" noValidate onSubmit={handleSubmit}>
                    <Stack spacing={3}>
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
                                        <IconButton onClick={handleShowPassword} edge="end">
                                            {/* <Iconify icon={showPassword ? 'eva:eye-fill' : 'eva:eye-off-fill'} /> */}
                                        </IconButton>
                                    </InputAdornment>
                                ),
                            }}
                            error={Boolean(touched.password && errors.password)}
                            helperText={touched.password && errors.password}
                        />
                    </Stack>

                    <Stack
                        direction="row"
                        justifyContent="space-between"
                        alignItems="center"
                        spacing={2}
                        margin={2}
                    >
                        <Link component={RouterLink} variant="subtitle2" to="/forgot_password" underline="hover">
                            Forgot password?
                        </Link>
                        <Link component={RouterLink} variant="subtitle2" to="/registor" underline="hover">
                            Registor
                        </Link>
                    </Stack>

                    <Button fullWidth size="large" type="submit" variant="contained" loading={isSubmitting}>
                        Login
                    </Button>
                </Form>
            </FormikProvider>
        </Container>
    );
}
