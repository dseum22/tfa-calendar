import { InertiaLink, useForm } from '@inertiajs/inertia-react'
import AuthLayout from '../../layouts/AuthLayout'

const Login = () => {
    const { data, setData, post, processing, errors, hasErrors } = useForm({
        email: '',
        password: '',
    })
    const handleChange = e => setData(e.target.name, e.target.value)
    const handleSubmit = e => {
        e.preventDefault()
        post('/login', {
            onError: () => setData('password', '')
        })
    }
    return (
        <AuthLayout>
            <AuthLayout.Title>Login</AuthLayout.Title>
            <AuthLayout.Content>
                <form className={hasErrors ? 'form-validate' : ''} onSubmit={handleSubmit} noValidate>
                    <div className="mb-3">
                        <input className={`form-field h-12 ${errors.email ? 'invalid' : ''}`} name="email" type="email" placeholder="Email" value={data.email} onChange={handleChange}></input>
                        {errors.email && <div className="form-error">{errors.email}</div>}
                    </div>
                    <div className="mb-6">
                        <input className={`form-field h-12 ${errors.password ? 'invalid' : ''}`} name="password" type="password" placeholder="Password" value={data.password} onChange={handleChange}></input>
                        {errors.password && <div className="form-error">{errors.password}</div>}
                    </div>
                    <button className="btn-positive w-full h-12" type="submit" disabled={processing}>Login</button>
                </form>
                <p className="text-center mt-3">Don't have an account? <InertiaLink className="link-inline" href="/register">Register here.</InertiaLink></p>
            </AuthLayout.Content>
        </AuthLayout>
    )
}

export default Login