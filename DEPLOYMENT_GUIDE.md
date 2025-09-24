# 🚀 University Portal Deployment Guide

## Quick Start (FREE)

### Option 1: Vercel + Railway (Recommended)

#### Frontend (Vercel)
1. Go to [vercel.com](https://vercel.com)
2. Sign up with GitHub
3. Import repository: `https://github.com/shopkoski/Uniportal`
4. Configure:
   - **Framework Preset**: Other
   - **Root Directory**: `frontend`
   - **Build Command**: (leave empty)
   - **Output Directory**: (leave empty)
5. Deploy!

#### Backend (Railway)
1. Go to [railway.app](https://railway.app)
2. Sign up with GitHub
3. Create new project from GitHub repo
4. Add service:
   - **Source**: GitHub repo
   - **Root Directory**: `backend`
   - **Build Command**: `dotnet build`
   - **Start Command**: `dotnet run`
5. Add PostgreSQL database
6. Configure environment variables:
   - `ConnectionStrings__DefaultConnection`: (Railway will provide)
7. Deploy!

### Option 2: Railway Full-Stack (Paid - $5/month)

1. Go to [railway.app](https://railway.app)
2. Create new project from GitHub repo
3. Add two services:
   - **Backend Service**: `backend/` folder
   - **Frontend Service**: `frontend/` folder
4. Add PostgreSQL database
5. Configure environment variables
6. Deploy both services

## Environment Variables

### Backend (.NET)
```bash
ConnectionStrings__DefaultConnection=your_database_connection_string
JWT__SecretKey=your_jwt_secret_key
JWT__Issuer=your_app_name
JWT__Audience=your_app_name
```

### Frontend (PHP)
```php
// Update frontend/database/config.php with production database settings
```

## Database Setup

1. **Development**: Uses local MySQL
2. **Production**: Use Railway PostgreSQL or external database
3. **Migration**: Run `dotnet ef database update` in backend folder

## Custom Domain (Optional)

1. Buy domain from Namecheap, GoDaddy, etc.
2. Add DNS records pointing to your hosting provider
3. Configure SSL certificate (usually automatic)

## Monitoring & Maintenance

1. **Logs**: Check hosting provider dashboard
2. **Updates**: Push to GitHub triggers automatic deployment
3. **Backups**: Database backups handled by hosting provider
4. **Performance**: Monitor usage and upgrade if needed

## Cost Breakdown

### Free Tier
- **Vercel**: Free (frontend)
- **Railway**: Free tier (backend + database)
- **Total**: $0/month

### Production Tier
- **Railway**: $5/month (backend + frontend + database)
- **Domain**: $10-15/year
- **Total**: ~$6/month

## Troubleshooting

### Common Issues
1. **Database Connection**: Check connection string
2. **CORS Issues**: Configure CORS in backend
3. **File Permissions**: Check PHP file permissions
4. **Environment Variables**: Verify all required variables are set

### Support
- Check hosting provider documentation
- Review application logs
- Test locally first before deploying
