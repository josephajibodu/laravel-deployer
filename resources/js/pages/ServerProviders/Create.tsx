import ServerProviderController from '@/actions/App/Http/Controllers/ServerProviderController';
import InputError from '@/components/input-error';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/react';
import { AlertCircle, ArrowLeft, Info, Server } from 'lucide-react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Server Providers',
        href: '/server-providers',
    },
    {
        title: 'Create',
        href: '/server-providers/create',
    },
];

const providerTypes = [
    { value: 'digitalocean', label: 'DigitalOcean', description: 'Cloud infrastructure platform' },
    { value: 'aws', label: 'Amazon Web Services', description: 'Comprehensive cloud platform' },
    { value: 'linode', label: 'Linode', description: 'Cloud computing platform' },
    { value: 'vultr', label: 'Vultr', description: 'High-performance cloud infrastructure' },
    { value: 'hetzner', label: 'Hetzner', description: 'European cloud provider' },
    { value: 'custom', label: 'Custom Provider', description: 'Custom API integration' },
];

const providerInstructions: Record<string, { title: string; description: string; fields: string[] }> = {
    digitalocean: {
        title: 'DigitalOcean Setup',
        description: 'To connect to DigitalOcean, you need to create a Personal Access Token.',
        fields: ['API Key (Personal Access Token)', 'Region (optional)'],
    },
    aws: {
        title: 'AWS Setup',
        description: 'To connect to AWS, you need your Access Key ID and Secret Access Key.',
        fields: ['API Key (Access Key ID)', 'Secret Key (Secret Access Key)', 'Region'],
    },
    linode: {
        title: 'Linode Setup',
        description: 'To connect to Linode, you need to create a Personal Access Token.',
        fields: ['API Key (Personal Access Token)', 'Region (optional)'],
    },
    vultr: {
        title: 'Vultr Setup',
        description: 'To connect to Vultr, you need your API Key.',
        fields: ['API Key', 'Region (optional)'],
    },
    hetzner: {
        title: 'Hetzner Setup',
        description: 'To connect to Hetzner, you need your API Token.',
        fields: ['API Key (API Token)', 'Region (optional)'],
    },
    custom: {
        title: 'Custom Provider Setup',
        description: 'Configure your custom provider with the appropriate credentials.',
        fields: ['API Key', 'Endpoint URL', 'Region (optional)'],
    },
};

export default function CreateServerProvider() {
    const [providerType, setProviderType] = useState<string>('digitalocean');
    const [credentials, setCredentials] = useState({
        api_key: '',
        secret_key: '',
        region: '',
        endpoint: '',
    });

    const currentInstructions = providerType ? providerInstructions[providerType] : null;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Server Provider" />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center gap-4">
                    <Link href="/server-providers">
                        <Button variant="outline" size="sm">
                            <ArrowLeft className="mr-2 h-4 w-4" />
                            Back
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Add Server Provider</h1>
                        <p className="text-muted-foreground">Connect to a cloud provider to manage your servers.</p>
                    </div>
                </div>

                <div className="grid gap-6 lg:grid-cols-3">
                    <div className="lg:col-span-2">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Server className="h-5 w-5" />
                                    Provider Configuration
                                </CardTitle>
                                <CardDescription>Configure your server provider connection details.</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <Form {...ServerProviderController.store.form()} className="space-y-6">
                                    <div className="space-y-2">
                                        <Label htmlFor="name">Provider Name</Label>
                                        <Input id="name" name="name" placeholder="e.g., My DigitalOcean Account" required />
                                        <InputError name="name" />
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="provider_type">Provider Type</Label>
                                        <input type="hidden" name="provider_type" value={providerType} />
                                        <Select value={providerType} onValueChange={setProviderType} required>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a provider" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {providerTypes.map((provider) => (
                                                    <SelectItem key={provider.value} value={provider.value}>
                                                        <div className="flex flex-col">
                                                            <span className="font-medium">{provider.label}</span>
                                                            <span className="text-xs text-muted-foreground">{provider.description}</span>
                                                        </div>
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError name="provider_type" />
                                    </div>

                                    {providerType && (
                                        <div className="space-y-4">
                                            <div className="space-y-2">
                                                <Label htmlFor="api_key">{providerType === 'aws' ? 'Access Key ID' : 'API Key'}</Label>
                                                <Input
                                                    id="api_key"
                                                    name="credentials[api_key]"
                                                    type="password"
                                                    value={credentials.api_key}
                                                    onChange={(e) => setCredentials((prev) => ({ ...prev, api_key: e.target.value }))}
                                                    placeholder="Enter your API key"
                                                    required
                                                />
                                                <InputError name="credentials.api_key" />
                                            </div>

                                            {providerType === 'aws' && (
                                                <div className="space-y-2">
                                                    <Label htmlFor="secret_key">Secret Access Key</Label>
                                                    <Input
                                                        id="secret_key"
                                                        name="credentials[secret_key]"
                                                        type="password"
                                                        value={credentials.secret_key}
                                                        onChange={(e) => setCredentials((prev) => ({ ...prev, secret_key: e.target.value }))}
                                                        placeholder="Enter your secret access key"
                                                        required
                                                    />
                                                    <InputError name="credentials.secret_key" />
                                                </div>
                                            )}

                                            <div className="space-y-2">
                                                <Label htmlFor="region">Region (Optional)</Label>
                                                <Input
                                                    id="region"
                                                    name="credentials[region]"
                                                    value={credentials.region}
                                                    onChange={(e) => setCredentials((prev) => ({ ...prev, region: e.target.value }))}
                                                    placeholder="e.g., nyc1, us-east-1, eu-central-1"
                                                />
                                                <InputError name="credentials.region" />
                                            </div>

                                            {providerType === 'custom' && (
                                                <div className="space-y-2">
                                                    <Label htmlFor="endpoint">Endpoint URL</Label>
                                                    <Input
                                                        id="endpoint"
                                                        name="credentials[endpoint]"
                                                        type="url"
                                                        value={credentials.endpoint}
                                                        onChange={(e) => setCredentials((prev) => ({ ...prev, endpoint: e.target.value }))}
                                                        placeholder="https://api.example.com"
                                                        required
                                                    />
                                                    <InputError name="credentials.endpoint" />
                                                </div>
                                            )}
                                        </div>
                                    )}

                                    <div className="flex gap-3">
                                        <Button type="submit" disabled={!providerType}>
                                            Create Provider
                                        </Button>
                                        <Link href="/server-providers">
                                            <Button type="button" variant="outline">
                                                Cancel
                                            </Button>
                                        </Link>
                                    </div>
                                </Form>
                            </CardContent>
                        </Card>
                    </div>

                    <div className="space-y-4">
                        {currentInstructions && (
                            <Card>
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2">
                                        <Info className="h-5 w-5" />
                                        {currentInstructions.title}
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <p className="text-sm text-muted-foreground">{currentInstructions.description}</p>

                                    <div>
                                        <h4 className="mb-2 text-sm font-medium">Required Fields:</h4>
                                        <ul className="space-y-1 text-sm text-muted-foreground">
                                            {currentInstructions.fields.map((field, index) => (
                                                <li key={index} className="flex items-center gap-2">
                                                    <div className="h-1 w-1 rounded-full bg-muted-foreground" />
                                                    {field}
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                </CardContent>
                            </Card>
                        )}

                        <Alert>
                            <AlertCircle className="h-4 w-4" />
                            <AlertDescription>
                                Your credentials are encrypted and stored securely. We never store your actual API keys in plain text.
                            </AlertDescription>
                        </Alert>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
