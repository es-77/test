import { useMutation, useQuery, useQueryClient } from "react-query";
import axios from "axios";
import laravelApi from "../../config/laravelApi";
import { authKeys } from "../../config/queryKeys";

export function useLogin() {
    const queryClient = useQueryClient();
    return useMutation(
        (user) => axios.post(laravelApi.auth.login, user).then((result) => result.data),
        {
            onSuccess: () => {
                // Invalidate and refetch


            },
        }
    );
}


export function useLogout() {
    const queryClient = useQueryClient();
    return useMutation(
        () => axios.post(laravelApi.auth.logout).then((result) => result.data),
        {
            onSuccess: () => {
                // Invalidate and refetch
                queryClient.invalidateQueries(authKeys.user);
            },
        }
    );
}

export function useNewPassword() {
    const queryClient = useQueryClient();
    return useMutation(
        (user) => axios.post(laravelApi.auth.newPassword, user).then((result) => result.data),
        {
            onSuccess: () => {
                // Invalidate and refetch


            },
        }
    );
}

export function useResetPassword() {
    const queryClient = useQueryClient();
    return useMutation(
        (user) => axios.post(laravelApi.auth.forgot, user).then((result) => result.data),
        {
            onSuccess: () => {
                // Invalidate and refetch


            },
        }
    );
}


export function useUser(token) {
    async function getUser() {
        if (token) {
            let user = null;
            await axios.get(laravelApi.auth.user).then((result) => {
                user = result.data?.data;
            });
            return user;
        }
        return null;
    }

    return useQuery(authKeys.user, getUser, {
        retry: false,
    });
}

// export function useProfileSave() {
export function useUpdateProfile() {
    const queryClient = useQueryClient();
    return useMutation(
        (users) => axios.post(laravelApi.users_save.userUpdated, users, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }).then((result) => result.data),
        {
            onSuccess: () => {
                // Invalidate and refetch
                queryClient.invalidateQueries(authKeys.user);


            },
        }
    );
}