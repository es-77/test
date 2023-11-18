import { QueryClient, QueryClientProvider } from 'react-query';
import { AuthProvider } from './context/AuthContext';
import Router from './Router';
import LocalToastContainer from './layouts/LocalToastContainer';

function App() {
  const queryClient = new QueryClient();
  queryClient.setDefaultOptions({
    queries: {
      refetchOnWindowFocus: true,
    },
  });
  return (
    <div className="App">
      <LocalToastContainer />
      <QueryClientProvider client={queryClient}>
        <AuthProvider>
          <Router />
        </AuthProvider>
      </QueryClientProvider>
    </div>
  );
}

export default App;
