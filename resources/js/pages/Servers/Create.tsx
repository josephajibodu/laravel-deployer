import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft, Server, Sparkles } from 'lucide-react';
import { useState } from 'react';

interface ServerProvider {
    id: number;
    name: string;
    provider_type: string;
}

interface ServersCreateProps {
    serverProviders: ServerProvider[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Servers',
        href: '/servers',
    },
    {
        title: 'Create',
        href: '/servers/create',
    },
];

const serverTypes = [
    { value: 'app', label: 'App server', description: 'A full-stack server that includes everything you need to run applications.' },
    { value: 'database', label: 'Database server', description: 'A database server that includes MySQL or Postgres.' },
    { value: 'cache', label: 'Cache server', description: 'A cache server for Redis or Memcached.' },
    { value: 'worker', label: 'Worker server', description: 'A background job processing server.' },
    { value: 'load_balancer', label: 'Load balancer', description: 'A load balancer that distributes traffic across multiple servers.' },
];

const regions = [
    { value: 'nyc1', label: 'New York 1', provider: 'digitalocean' },
    { value: 'sfo2', label: 'San Francisco 2', provider: 'digitalocean' },
    { value: 'fra1', label: 'Frankfurt 1', provider: 'digitalocean' },
    { value: 'sgp1', label: 'Singapore 1', provider: 'digitalocean' },
    { value: 'us-east-1', label: 'US East (N. Virginia)', provider: 'aws' },
    { value: 'us-west-2', label: 'US West (Oregon)', provider: 'aws' },
    { value: 'eu-west-1', label: 'Europe (Ireland)', provider: 'aws' },
    { value: 'ap-southeast-1', label: 'Asia Pacific (Singapore)', provider: 'aws' },
];

const serverSizes = [
    { value: 's-1vcpu-512mb', label: '512 MB RAM · 1 vCPU Core · 10 GB SSD', price: '$4/mo' },
    { value: 's-1vcpu-1gb', label: '1 GB RAM · 1 vCPU Core · 25 GB SSD', price: '$6/mo' },
    { value: 's-1vcpu-1gb-intel', label: '1 GB RAM (Premium Intel) · 1 vCPU Core · 25 GB SSD', price: '$7/mo' },
    { value: 's-1vcpu-1gb-amd', label: '1 GB RAM (Premium AMD) · 1 vCPU Core · 25 GB SSD', price: '$7/mo' },
    { value: 's-2vcpu-2gb', label: '2 GB RAM · 2 vCPU Cores · 50 GB SSD', price: '$12/mo' },
    { value: 's-2vcpu-4gb', label: '4 GB RAM · 2 vCPU Cores · 80 GB SSD', price: '$24/mo' },
];

export default function Create({ serverProviders }: ServersCreateProps) {
    const [step, setStep] = useState(1);
    const [formData, setFormData] = useState({
        name: '',
        server_provider_id: '',
        server_type: 'app',
        region: '',
        server_size: '',
        operating_system: 'Ubuntu 24.04',
        php_version: '8.3',
    });

    const generateServerName = () => {
        const adjectives = ['fathomless', 'mysterious', 'ancient', 'cosmic', 'ethereal', 'luminous', 'prismatic', 'stellar'];
        const nouns = ['rome', 'athens', 'sparta', 'carthage', 'babylon', 'troy', 'thebes', 'corinth'];
        const adjective = adjectives[Math.floor(Math.random() * adjectives.length)];
        const noun = nouns[Math.floor(Math.random() * nouns.length)];
        return `${adjective}-${noun}`;
    };

    const handleNameGenerate = () => {
        setFormData((prev) => ({ ...prev, name: generateServerName() }));
    };

    const selectedProvider = serverProviders.find((p) => p.id.toString() === formData.server_provider_id);
    const availableRegions = regions.filter((r) => !selectedProvider || r.provider === selectedProvider.provider_type);

    const canProceedToStep2 = formData.name && formData.server_provider_id;
    const canCreateServer = canProceedToStep2 && formData.region && formData.server_size;

    if (step === 1) {
        return (
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Create Server" />
                <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                    <div className="flex items-center gap-4">
                        <Link href="/servers">
                            <Button variant="outline" size="sm">
                                <ArrowLeft className="mr-2 h-4 w-4" />
                                Back
                            </Button>
                        </Link>
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight">Create a new server</h1>
                            <p className="text-muted-foreground">
                                Deploy your app in minutes with an all-in-one provisioned server.{' '}
                                <a href="#" className="text-blue-500 hover:underline">
                                    Learn more
                                </a>
                            </p>
                        </div>
                    </div>

                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <Server className="h-5 w-5" />
                                Server Configuration
                            </CardTitle>
                            <CardDescription>Configure your server name and provider.</CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div className="space-y-2">
                                <div className="flex items-center justify-between">
                                    <Label htmlFor="name">Name</Label>
                                    <Button type="button" variant="link" size="sm" onClick={handleNameGenerate} className="text-blue-500">
                                        <Sparkles className="mr-1 h-3 w-3" />
                                        Generate new name
                                    </Button>
                                </div>
                                <Input
                                    id="name"
                                    value={formData.name}
                                    onChange={(e) => setFormData((prev) => ({ ...prev, name: e.target.value }))}
                                    placeholder="e.g., my-awesome-server"
                                    required
                                />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="provider">Provider</Label>
                                <Select
                                    value={formData.server_provider_id}
                                    onValueChange={(value) => setFormData((prev) => ({ ...prev, server_provider_id: value }))}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select a provider" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {serverProviders.map((provider) => (
                                            <SelectItem key={provider.id} value={provider.id.toString()}>
                                                <div className="flex items-center gap-2">
                                                    <div className="h-2 w-2 rounded-full bg-blue-500" />
                                                    {provider.name}
                                                </div>
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {serverProviders.length === 0 && (
                                    <p className="text-sm text-muted-foreground">
                                        No server providers available.{' '}
                                        <Link href="/server-providers/create" className="text-blue-500 hover:underline">
                                            Create one first
                                        </Link>
                                        .
                                    </p>
                                )}
                            </div>

                            <div className="flex justify-end">
                                <Button onClick={() => setStep(2)} disabled={!canProceedToStep2}>
                                    Continue
                                    <ArrowLeft className="ml-2 h-4 w-4 rotate-180" />
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </AppLayout>
        );
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Configure Server" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center gap-4">
                    <Link href="/servers">
                        <Button variant="outline" size="sm">
                            <ArrowLeft className="mr-2 h-4 w-4" />
                            Back
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Configure {formData.name}</h1>
                        <p className="text-muted-foreground">Configure your server settings and specifications.</p>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Server className="h-5 w-5" />
                            Server Configuration
                        </CardTitle>
                        <CardDescription>Configure your server type, region, and size.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form action="/servers" method="post" className="space-y-6">
                            <input type="hidden" name="name" value={formData.name} />
                            <input type="hidden" name="server_provider_id" value={formData.server_provider_id} />
                            <input type="hidden" name="server_type" value={formData.server_type} />
                            <input type="hidden" name="region" value={formData.region} />
                            <input type="hidden" name="server_size" value={formData.server_size} />
                            <input type="hidden" name="operating_system" value={formData.operating_system} />
                            <input type="hidden" name="php_version" value={formData.php_version} />

                            <div className="space-y-2">
                                <Label htmlFor="server_type">Type</Label>
                                <Select
                                    value={formData.server_type}
                                    onValueChange={(value) => setFormData((prev) => ({ ...prev, server_type: value }))}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select a server type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {serverTypes.map((type) => (
                                            <SelectItem key={type.value} value={type.value}>
                                                <div className="flex flex-col">
                                                    <span className="font-medium">{type.label}</span>
                                                    <span className="text-xs text-muted-foreground">{type.description}</span>
                                                </div>
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError name="server_type" />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="region">Region</Label>
                                <Select value={formData.region} onValueChange={(value) => setFormData((prev) => ({ ...prev, region: value }))}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select a region" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {availableRegions.map((region) => (
                                            <SelectItem key={region.value} value={region.value}>
                                                {region.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError name="region" />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="server_size">Server size</Label>
                                <p className="text-sm text-muted-foreground">
                                    Prices shown below are estimates only. Please refer to the{' '}
                                    <a href="#" className="text-blue-500 hover:underline">
                                        {selectedProvider?.provider_type === 'digitalocean' ? 'DigitalOcean' : 'Akamai'} pricing page
                                    </a>{' '}
                                    for the latest pricing.
                                </p>
                                <Select
                                    value={formData.server_size}
                                    onValueChange={(value) => setFormData((prev) => ({ ...prev, server_size: value }))}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select a server size" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {serverSizes.map((size) => (
                                            <SelectItem key={size.value} value={size.value}>
                                                <div className="flex w-full items-center justify-between">
                                                    <span>{size.label}</span>
                                                    <span className="ml-4 text-sm text-muted-foreground">{size.price}</span>
                                                </div>
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError name="server_size" />
                            </div>

                            {!canCreateServer && (
                                <div className="rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/20">
                                    <p className="text-sm text-yellow-800 dark:text-yellow-200">
                                        Please select a region and server size to continue.
                                        <br />
                                        <span className="text-xs">
                                            Missing: {!formData.region && 'Region '}
                                            {!formData.server_size && 'Server Size'}
                                        </span>
                                    </p>
                                </div>
                            )}

                            <div className="flex items-center justify-between">
                                <Button type="button" variant="outline" onClick={() => setStep(1)}>
                                    <ArrowLeft className="mr-2 h-4 w-4" />
                                    Back
                                </Button>
                                <Button type="submit" disabled={!canCreateServer}>
                                    Create server
                                </Button>
                            </div>
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
