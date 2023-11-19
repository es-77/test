import * as React from 'react';
import PropTypes from 'prop-types';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import CssBaseline from '@mui/material/CssBaseline';
import Divider from '@mui/material/Divider';
import Drawer from '@mui/material/Drawer';
import IconButton from '@mui/material/IconButton';
import List from '@mui/material/List';
import ListItem from '@mui/material/ListItem';
import ListItemButton from '@mui/material/ListItemButton';
import ListItemText from '@mui/material/ListItemText';
import MenuIcon from '@mui/icons-material/Menu';
import Toolbar from '@mui/material/Toolbar';
import Typography from '@mui/material/Typography';
import { Link } from 'react-router-dom';
import { MenuItem, Stack } from '@mui/material';
import { useContext } from 'react';
import { AuthContext } from '../../context/AuthContext';

const drawerWidth = 240;

function DrawerAppBar(props) {

    const { user } = useContext(AuthContext);
    const navItems = [
        (user && user?.type === "admin") ? { component: 'Users', path: "/user" } : "",
        (user && user?.type === "admin") ? { component: 'Comments', path: "/comment" } : "",
        (user && user?.type === "admin") ? { component: 'Feed Back', path: "/feedback" } : "",
        { component: 'Give Feedback On Comment', path: "/give_feedback_on_comment" },

    ];
    const { window } = props;
    const [mobileOpen, setMobileOpen] = React.useState(false);

    const handleDrawerToggle = () => {
        setMobileOpen((prevState) => !prevState);
    };

    const drawer = (
        <Box onClick={handleDrawerToggle} sx={{ textAlign: 'center' }}>
            <Typography variant="h6" sx={{ my: 2 }}>
                Ikonic Test
            </Typography>
            <Divider />
            <List>
                {navItems.map((item) => (
                    <ListItem key={item?.component} disablePadding>
                        <ListItemButton component={Link} to={`/dashboard${item?.path}`} sx={{ textAlign: 'center' }}>
                            <ListItemText primary={item?.component} />
                        </ListItemButton>
                    </ListItem>
                ))}
            </List>
        </Box>
    );

    const container = window !== undefined ? () => window().document.body : undefined;

    return (
        <Box sx={{ display: 'flex' }}>
            <CssBaseline />
            <AppBar component="nav">
                <Toolbar>
                    <IconButton
                        color="inherit"
                        aria-label="open drawer"
                        edge="start"
                        onClick={handleDrawerToggle}
                        sx={{ mr: 2, display: { sm: 'none' } }}
                    >
                        <MenuIcon />
                    </IconButton>
                    <Typography
                        variant="h6"
                        component="div"
                        sx={{ flexGrow: 1, display: { xs: 'none', sm: 'block' } }}
                    >
                        Ikonic Test
                    </Typography>
                    <Box sx={{ display: { xs: 'none', sm: 'block', display: 'flex', flexDirection: 'row', justifyContent: 'center' } }}>
                        <Stack direction="row" spacing={2}>
                            {/* <List> */}
                            {navItems.map((item) => (
                                <MenuItem key={item?.component} component={Link} to={`/dashboard${item?.path}`} >
                                    <Typography textAlign="center">{item?.component}</Typography>
                                </MenuItem>
                            ))}
                        </Stack>
                    </Box>
                </Toolbar>
            </AppBar>
            <nav>
                <Drawer
                    container={container}
                    variant="temporary"
                    open={mobileOpen}
                    onClose={handleDrawerToggle}
                    ModalProps={{
                        keepMounted: true, // Better open performance on mobile.
                    }}
                    sx={{
                        display: { xs: 'block', sm: 'none' },
                        '& .MuiDrawer-paper': { boxSizing: 'border-box', width: drawerWidth },
                    }}
                >
                    {drawer}
                </Drawer>
            </nav>
            <Box component="main" sx={{ p: 3 }}>
                <Toolbar />
            </Box>
        </Box>
    );
}

DrawerAppBar.propTypes = {
    /**
     * Injected by the documentation to work in an iframe.
     * You won't need it on your project.
     */
    window: PropTypes.func,
};

export default DrawerAppBar;