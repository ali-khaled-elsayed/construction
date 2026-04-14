import Link from 'next/link';
import { Search, Shield, Star, Users, CheckCircle, ArrowRight } from 'lucide-react';

export default function Home() {
  return (
    <div className="min-h-screen bg-white">
      {/* Header */}
      <header className="bg-white shadow-sm sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center py-4">
            <Link href="/" className="flex items-center">
              <span className="text-2xl font-bold text-blue-600">BuildPro</span>
            </Link>
            <nav className="hidden md:flex space-x-8">
              <Link href="/jobs" className="text-gray-700 hover:text-blue-600">Find Professionals</Link>
              <Link href="/providers" className="text-gray-700 hover:text-blue-600">Browse Services</Link>
              <Link href="/how-it-works" className="text-gray-700 hover:text-blue-600">How it Works</Link>
            </nav>
            <div className="flex items-center space-x-4">
              <Link href="/auth/login" className="text-gray-700 hover:text-blue-600">Log In</Link>
              <Link href="/auth/register" className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Sign Up
              </Link>
            </div>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section className="bg-gradient-to-br from-blue-50 to-blue-100 py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
            Find Trusted Construction Professionals
          </h1>
          <p className="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
            Connect with verified builders, plumbers, electricians, and more for your construction and renovation projects.
          </p>
          
          {/* Search Bar */}
          <div className="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-4">
            <div className="flex flex-col md:flex-row gap-4">
              <div className="flex-1">
                <input
                  type="text"
                  placeholder="What service do you need? (e.g., plumber, electrician)"
                  className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              <div className="md:w-48">
                <select className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                  <option value="">All Locations</option>
                  <option value="cairo">Cairo</option>
                  <option value="giza">Giza</option>
                  <option value="alexandria">Alexandria</option>
                </select>
              </div>
              <button className="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold flex items-center justify-center gap-2">
                <Search className="w-5 h-5" />
                Search
              </button>
            </div>
          </div>

          {/* Popular Searches */}
          <div className="mt-8">
            <p className="text-gray-600 mb-4">Popular services:</p>
            <div className="flex flex-wrap justify-center gap-3">
              {['Plumbers', 'Electricians', 'Builders', 'Painters', 'Carpenters', 'HVAC', 'Roofers', 'Flooring'].map((service) => (
                <Link
                  key={service}
                  href={`/jobs?category=${service.toLowerCase()}`}
                  className="bg-white px-4 py-2 rounded-full shadow-sm hover:shadow-md transition-shadow text-gray-700 hover:text-blue-600"
                >
                  {service}
                </Link>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Trust Indicators */}
      <section className="py-12 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="flex items-center gap-4 p-6 bg-gray-50 rounded-xl">
              <Shield className="w-12 h-12 text-blue-600" />
              <div>
                <h3 className="font-bold text-lg">Verified Professionals</h3>
                <p className="text-gray-600">All professionals are background checked and verified</p>
              </div>
            </div>
            <div className="flex items-center gap-4 p-6 bg-gray-50 rounded-xl">
              <Star className="w-12 h-12 text-blue-600" />
              <div>
                <h3 className="font-bold text-lg">Rated & Reviewed</h3>
                <p className="text-gray-600">Real reviews from real customers</p>
              </div>
            </div>
            <div className="flex items-center gap-4 p-6 bg-gray-50 rounded-xl">
              <Users className="w-12 h-12 text-blue-600" />
              <div>
                <h3 className="font-bold text-lg">5000+ Professionals</h3>
                <p className="text-gray-600">Find the right expert for any job</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* How it Works */}
      <section className="py-20 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-4xl font-bold text-center mb-12">How It Works</h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              { step: 1, title: 'Post Your Job', description: 'Tell us what you need done and get quotes from professionals.' },
              { step: 2, title: 'Choose Your Pro', description: 'Compare quotes, read reviews, and pick the best match.' },
              { step: 3, title: 'Get It Done', description: 'Your professional completes the job to your satisfaction.' },
            ].map((item) => (
              <div key={item.step} className="bg-white p-8 rounded-xl shadow-sm text-center">
                <div className="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">
                  {item.step}
                </div>
                <h3 className="text-xl font-bold mb-4">{item.title}</h3>
                <p className="text-gray-600">{item.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Categories */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-4xl font-bold text-center mb-12">Popular Categories</h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
            {[
              { name: 'Plumbing', icon: '🔧', count: '500+ pros' },
              { name: 'Electrical', icon: '⚡', count: '400+ pros' },
              { name: 'Building', icon: '🏗️', count: '600+ pros' },
              { name: 'Painting', icon: '🎨', count: '350+ pros' },
              { name: 'Carpentry', icon: '🪚', count: '300+ pros' },
              { name: 'HVAC', icon: '❄️', count: '250+ pros' },
              { name: 'Roofing', icon: '🏠', count: '200+ pros' },
              { name: 'Flooring', icon: '🪵', count: '280+ pros' },
            ].map((category) => (
              <Link
                key={category.name}
                href={`/jobs?category=${category.name.toLowerCase()}`}
                className="bg-gray-50 p-6 rounded-xl hover:shadow-lg transition-shadow text-center group"
              >
                <div className="text-4xl mb-4">{category.icon}</div>
                <h3 className="font-bold text-lg group-hover:text-blue-600">{category.name}</h3>
                <p className="text-gray-600 text-sm">{category.count}</p>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-blue-600 text-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-4xl font-bold mb-6">Ready to Get Started?</h2>
          <p className="text-xl mb-8 opacity-90">
            Join thousands of homeowners and professionals on BuildPro
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              href="/auth/register?role=customer"
              className="bg-white text-blue-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 flex items-center justify-center gap-2"
            >
              Hire a Professional
              <ArrowRight className="w-5 h-5" />
            </Link>
            <Link
              href="/auth/register?role=service_provider"
              className="bg-blue-700 text-white px-8 py-4 rounded-lg font-bold hover:bg-blue-800 flex items-center justify-center gap-2"
            >
              Join as a Professional
              <ArrowRight className="w-5 h-5" />
            </Link>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-gray-300 py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
              <span className="text-2xl font-bold text-white">BuildPro</span>
              <p className="mt-4 text-gray-400">Connecting homeowners with trusted construction professionals.</p>
            </div>
            <div>
              <h4 className="font-bold text-white mb-4">Company</h4>
              <ul className="space-y-2">
                <li><Link href="/about" className="hover:text-white">About Us</Link></li>
                <li><Link href="/careers" className="hover:text-white">Careers</Link></li>
                <li><Link href="/press" className="hover:text-white">Press</Link></li>
              </ul>
            </div>
            <div>
              <h4 className="font-bold text-white mb-4">Support</h4>
              <ul className="space-y-2">
                <li><Link href="/help" className="hover:text-white">Help Center</Link></li>
                <li><Link href="/safety" className="hover:text-white">Safety</Link></li>
                <li><Link href="/contact" className="hover:text-white">Contact Us</Link></li>
              </ul>
            </div>
            <div>
              <h4 className="font-bold text-white mb-4">Legal</h4>
              <ul className="space-y-2">
                <li><Link href="/terms" className="hover:text-white">Terms of Service</Link></li>
                <li><Link href="/privacy" className="hover:text-white">Privacy Policy</Link></li>
                <li><Link href="/cookies" className="hover:text-white">Cookie Policy</Link></li>
              </ul>
            </div>
          </div>
          <div className="mt-12 pt-8 border-t border-gray-800 text-center">
            <p>&copy; 2024 BuildPro. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  );
}