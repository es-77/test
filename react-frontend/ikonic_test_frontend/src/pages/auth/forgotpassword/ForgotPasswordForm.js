import { Button, Container, Link, Stack, TextField } from '@mui/material';
import { Form, FormikProvider, useFormik } from 'formik';
import { Link as RouterLink } from 'react-router-dom';
import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import * as Yup from 'yup';
import { useResetPassword } from '../../../hooks/api/authQueries';
// form

// ----------------------------------------------------------------------

export default function ForgotPasswordForm() {
    const navigate = useNavigate();

    const forGotPasswordQuery = useResetPassword();

    const LoginSchema = Yup.object().shape({
        email: Yup.string().email('Email must be a valid email address').required('Email is required')
    });

    const formik = useFormik({
        initialValues: {
            email: ''
        },
        validationSchema: LoginSchema,
        onSubmit: (data) => {
            forGotPasswordQuery.mutate(data);
        },
    });


    const { errors, touched, values, isSubmitting, handleSubmit, getFieldProps, setSubmitting } = formik;



    useEffect(() => {

        if (forGotPasswordQuery?.data?.response === 100) {
            setSubmitting(false);
            const message = forGotPasswordQuery.data?.message;
            toast.error(message);
        }
        if (forGotPasswordQuery?.data?.response === 101) {
            const message = forGotPasswordQuery.data?.message;
            setSubmitting(false);
            toast.success(message);
            setTimeout(() => {
                navigate('/new');
            }, 2000);
        }
    }, [forGotPasswordQuery.data, forGotPasswordQuery.isError])


    return (
        <Container maxWidth="sm">

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

                        <Stack
                            direction="row"
                            justifyContent="space-between"
                            alignItems="center"
                            spacing={2}
                            margin={2}
                        >
                            <Link component={RouterLink} variant="subtitle2" to="/login" underline="hover">
                                Login page
                            </Link>
                            <Link component={RouterLink} variant="subtitle2" to="/registor" underline="hover">
                                Registor ?
                            </Link>
                        </Stack>

                        <Button fullWidth size="large" type="submit" variant="contained" loading={isSubmitting}>
                            Reset Password
                        </Button>
                    </Stack>
                </Form>
            </FormikProvider>
        </Container>
    );
}
