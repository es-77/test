import React, { useState, useEffect } from "react";
import { toast } from "react-toastify";
import { useNavigate } from "react-router-dom";
import { useLogout, useUser } from "../hooks/api/authQueries";

export const AuthContext = React.createContext();

export const AuthProvider = ({ children }) => {
    let tokenLocal;
    try {
        tokenLocal = localStorage.getItem("token");
    } catch (err) {
        tokenLocal = null;
    }

    const [token, setToken] = useState(tokenLocal);
    const [user, setUser] = useState(null);
    const { data: userReceived, isError, isSuccess, refetch } = useUser(token);
    const logoutQuery = useLogout();
    const navigate = useNavigate();

    useEffect(() => {
        if (isSuccess && userReceived) {
            setUser(userReceived);
        } else if (isError) {
            localStorage.removeItem("token");
            navigate('/login');
        }
    }, [isError, isSuccess, userReceived]);

    useEffect(() => {
        if (logoutQuery.isSuccess) {
            setUser(null);
            setToken(null);
        }
    }, [logoutQuery.isSuccess])

    const reloadUser = () => {
        refetch();
    };

    const logoutUser = () => {
        logoutQuery.mutate();

        localStorage.removeItem("token");
        navigate('/login');
        toast.success("Logged out successfully!");
    };

    const loginUser = (user, token) => {
        localStorage.setItem("token", token);
        setToken(token);
        setUser(user);
    };

    return (
        <AuthContext.Provider
            value={{
                token,
                setToken,
                user,
                setUser,
                reloadUser,
                loginUser,
                logoutUser,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
};
