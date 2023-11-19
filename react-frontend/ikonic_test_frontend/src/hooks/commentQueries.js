import axios from 'axios';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import laravelApi from '../config/laravelApi';
import { commentsKeys, feedbackCommensKeys } from '../config/queryKeys';

export function useComment(filters) {
    async function getAdmin() {
        let data = null;
        await axios.get(laravelApi.comments.comment, { params: filters }).then((result) => {
            data = result.data?.data;
        });
        return data;
    }

    return useQuery([commentsKeys.comment, filters], getAdmin, { staleTime: Infinity });
}

export function useSaveComment() {
    const queryClient = useQueryClient();
    return useMutation(
        (admin) =>
            axios
                .post(laravelApi.comments.comment, admin, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((result) => result.data),
        {
            onSuccess: () => {
                queryClient.invalidateQueries(commentsKeys?.comment);
                queryClient.invalidateQueries(feedbackCommensKeys.feedbackCommen);
            },
        }
    );
}

export function useUpdateComment() {
    const queryClient = useQueryClient();
    return useMutation(
        (admin) =>
            axios
                .put(laravelApi.comments.comment + '/' + admin?.commentId, admin)
                .then((result) => result.data),
        {
            onSuccess: () => {
                queryClient.invalidateQueries(commentsKeys?.comment);
                queryClient.invalidateQueries(feedbackCommensKeys.feedbackCommen);
            },
        }
    );
}

export function useDeleteComment() {
    const queryClient = useQueryClient();
    return useMutation((id) => axios.delete(`${laravelApi.comments.comment}/${id}`, {}).then((result) => result.data), {
        onSuccess: () => {
            // Invalidate and refetch
            queryClient.invalidateQueries(commentsKeys?.comment);
            queryClient.invalidateQueries(feedbackCommensKeys.feedbackCommen);
        },
    });
}
