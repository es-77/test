import axios from 'axios';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import laravelApi from '../config/laravelApi';
import { userKeys } from '../config/queryKeys';

export function useUser(filters) {
    async function getAdmin() {
        let data = null;
        await axios.get(laravelApi.users.admin, { params: filters }).then((result) => {
            data = result.data?.data;
        });
        return data;
    }

    return useQuery([userKeys.users, filters], getAdmin, { staleTime: Infinity });
}

export function useSaveUser() {
    const queryClient = useQueryClient();
    return useMutation(
        (admin) =>
            axios
                .post(laravelApi.users.admin, admin, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((result) => result.data),
        {
            onSuccess: () => {
                queryClient.invalidateQueries(userKeys.users);
            },
        }
    );
}

export function useUpdateUser() {
    const queryClient = useQueryClient();
    return useMutation(
        (admin) =>
            axios
                .put(laravelApi.users.admin + '/' + admin?.userId, admin)
                .then((result) => result.data),
        {
            onSuccess: () => {
                queryClient.invalidateQueries(userKeys.users);
            },
        }
    );
}

export function useDeleteUser() {
    const queryClient = useQueryClient();
    return useMutation((id) => axios.delete(`${laravelApi.users.admin}/${id}`, {}).then((result) => result.data), {
        onSuccess: () => {
            // Invalidate and refetch
            queryClient.invalidateQueries(userKeys.users);
        },
    });
}
