import axios from 'axios';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import laravelApi from '../config/laravelApi';
import { feedbacksKeys } from '../config/queryKeys';

export function useFeedback(filters) {
    async function getAdmin() {
        let data = null;
        await axios.get(laravelApi.feedbacks.feedback, { params: filters }).then((result) => {
            data = result.data?.data;
        });
        return data;
    }

    return useQuery([feedbacksKeys.feedback, filters], getAdmin, { staleTime: Infinity });
}

export function useSaveFeedback() {
    const queryClient = useQueryClient();
    return useMutation(
        (admin) =>
            axios
                .post(laravelApi.feedbacks.feedback, admin, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((result) => result.data),
        {
            onSuccess: () => {
                queryClient.invalidateQueries(feedbacksKeys.feedback);
            },
        }
    );
}

export function useUpdateFeedback() {
    const queryClient = useQueryClient();
    return useMutation(
        (admin, feedbackId) =>
            axios.put(`${laravelApi.feedbacks.feedback}/${admin?.feedbackId}`, admin).then((result) => result.data),
        {
            onSuccess: () => {
                queryClient.invalidateQueries(feedbacksKeys.feedback);
            },
        }
    );
}



export function useDeleteFeedback() {
    const queryClient = useQueryClient();
    return useMutation((id) => axios.delete(`${laravelApi.feedbacks.feedback}/${id}`, {}).then((result) => result.data), {
        onSuccess: () => {
            // Invalidate and refetch
            queryClient.invalidateQueries(feedbacksKeys.feedback);
        },
    });
}
