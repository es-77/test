import axios from 'axios';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import laravelApi from '../config/laravelApi';
import { feedbackCommensKeys } from '../config/queryKeys';

export function useFeedbackComment(filters) {
    async function getAdmin() {
        let data = null;
        await axios.get(laravelApi.feedbacksCommens.feedbackCommen, { params: filters }).then((result) => {
            data = result.data?.data;
        });
        return data;
    }

    return useQuery([feedbackCommensKeys.feedbackCommen, filters], getAdmin, { staleTime: Infinity });
}

export function useSaveFeedback() {
    const queryClient = useQueryClient();
    return useMutation(
        (admin) =>
            axios
                .post(laravelApi.feedbacksCommens.feedbackCommen, admin, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((result) => result.data),
        {
            onSuccess: () => {
                queryClient.invalidateQueries(feedbackCommensKeys.feedbackCommen);
            },
        }
    );
}

export function useUpdateFeedback() {
    const queryClient = useQueryClient();
    return useMutation(
        (admin, feedbackId) =>
            axios.put(`${laravelApi.feedbacksCommens.feedbackCommen}/${admin?.feedbackId}`, admin).then((result) => result.data),
        {
            onSuccess: () => {
                queryClient.invalidateQueries(feedbackCommensKeys.feedbackCommen);
            },
        }
    );
}



export function useDeleteFeedback() {
    const queryClient = useQueryClient();
    return useMutation((id) => axios.delete(`${laravelApi.feedbacksCommens.feedbackCommen}/${id}`, {}).then((result) => result.data), {
        onSuccess: () => {
            // Invalidate and refetch
            queryClient.invalidateQueries(feedbackCommensKeys.feedbackCommen);
        },
    });
}
